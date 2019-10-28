<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Utils\Encryption; 
use App\Models\Tips; 
use App\Models\BankCards;

class BallanceController extends Controller
{ 
    public function index()
    {  
    	$data = [
    		'total_amount' => Tips::forWithdraw()->sum('amount'),
    		'bank_cards'   => BankCards::where('id_user', \Auth::user()->id)->with('card_type')->get()
    	];

        return view('profile.ballance', $data);
    }  

    public function addCreditCard($lang, Request $request, Encryption $crypt)
    { 
    	try {
    		$this->validateCreditCardForm($request);
    	} catch (\Exception $e) {
    		return \JsonResponse::error(['messages' => $e->getMessage()]);
    	} 

        $expiryDate = prepareExpiryDate($request->expiry_date, true);
        BankCards::create([
        	'id_user'     => \Auth::id(),
        	'name'        => $request->name,
        	'type'        => $this->validatecard($request->number),
        	'number'      => $crypt->encrypt($request->number),
        	'hide_number' => self::getHideCardNumber($request->number),
        	'month'       => $expiryDate[0],
        	'year'        => $expiryDate[1],
        	'cvc'         => $crypt->encrypt($request->cvc)
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    } 

    public function deleteCreditCard($lang, $id)
    { 
        $card = BankCards::where('id_user', \Auth::user()->id)->whereId($id)->firstOrFail();
        $card->delete();
        return redirect()->back()->with('lk_success', 'Запись успешно удалена');
    }

    public function withdrawFunds($lang, Request $request, Encryption $crypt, $payoutService VisaPayment)
    {
    	$totalAmount = Tips::forWithdraw()->sum('amount'); 
    	try {
    		$this->validateCreditCardForm($request, $totalAmount);
    	} catch (\Exception $e) {
    		return \JsonResponse::error(['messages' => $e->getMessage()]);
    	}

    	$card     = BankCards::whereId($request->card)->first(); 
        $withdraw = $this->registerWithdraw($request);
          
        $payoutService->setOrderId($withdraw->rand)
                     ->setAmount(toFloat($withdraw->amount))
                     ->setDescription('Вывод средств официанту ' . $withdraw->user->name)
                     ->setCardCredentials([
                        'name'   => $card->name,
                        'number' => $crypt->decrypt($card->number),
                        'month'  => $card->month,
                        'year'   => $card->year,
                        'cvc'    => $crypt->decrypt($card->cvc),
                     ]);

        $payoutResponse = $payoutService->payout();  
        
        exit(print_arr($payoutResponse));
    }

    private function registerWithdraw($request)
    {
    	$id = WithdrawTips::create([
    		'id_user' => \Auth::id(),
    		'id_card' => $request->card,
    		'rand'    => generate_id(7),
    		'amount'  => toFloat($request->price)
    	])->id;
    	return WithdrawTips::whereId($id)->first();
    }

    private function validateWithdrawFunds($request, $totalAmount)
    {
    	if (!$request->price or !$request->card) 
    	{
    		throw new \Exception(\Constant::get('REQ_FIELDS')); 
    	} 

    	if ($request->price > $totalAmount) 
    	{
    		throw new \Exception("На вашем счету нет столько средств. Вы можете вывести не более {$totalAmount} Р");
    	} 

    	if (!BankCards::whereId($request->card)->count()) 
    	{
    		throw new \Exception("Ошибка");
    	} 
    } 

    private function validateCreditCardForm($request)
    {
    	if (!$request->name or !$request->number or !$request->expiry_date or !$request->cvc) 
        {
            throw new \Exception(\Constant::get('REQ_FIELDS')); 
        }

        $cardType = $this->validatecard($request->number);
        if ($cardType === false) 
        {
        	throw new \Exception('Этот номер кредитной карты недействителен');  
        }

        $expiryDate = prepareExpiryDate($request->expiry_date, true);
        if (date('y') > $expiryDate[1] or date('y') == $expiryDate[1] && date('m') > $expiryDate[0]) 
        {
        	throw new \Exception('Срок действия карты не действителен');  
        }
    }

    private static function getHideCardNumber($cardNumber)
    {
    	return substr($cardNumber, 0, 4) . ' ... ... ..' . substr($cardNumber, strlen($cardNumber)-2, 2);
    }

    private static function validateCard($number)
 	{ 
	    $cardtype = array(
	        "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
	        "mastercard" => "/^5[1-5][0-9]{14}$/",
	        "amex"       => "/^3[47][0-9]{13}$/",
	        "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
	    );

	    if (preg_match($cardtype['visa'],$number))
	    {
			$type= "visa";
	        return 'visa';
		
	    }
	    else if (preg_match($cardtype['mastercard'],$number))
	    {
			$type= "mastercard";
	        return 'mastercard';
	    }
	    else if (preg_match($cardtype['amex'],$number))
	    {
			$type= "amex";
	        return 'amex';
		
	    }
	    else if (preg_match($cardtype['discover'],$number))
	    {
			$type= "discover";
	        return 'discover';
	    }
	    else
	    {
	        return false;
	    } 
	} 
}