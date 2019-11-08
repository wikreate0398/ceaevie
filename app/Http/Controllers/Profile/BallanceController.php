<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Utils\Payments\VisaPayment;
use App\Utils\Payments\WithdrawalService; 
use App\Utils\Encryption; 
use App\Utils\Ballance;  
use App\Notifications\NewWithdrawalRequest;
use App\Models\Tips; 
use App\Models\BankCards;
use App\Models\WithdrawTips; 
use App\Models\AdminUser;

class BallanceController extends Controller
{ 
    public function index()
    {  
        $data = [
            'total_amount' => \Auth::user()->ballance,
            'bank_cards'   => BankCards::where('id_user', \Auth::user()->id)->with('card_type')->get(),
            'withdraws'    => WithdrawTips::where('id_user', \Auth::user()->id)
                                          // ->whidrawHistory()
                                          ->with('card')
                                          ->filter()
                                          ->orderByRaw('id desc')
                                          ->paginate(self::getPerPage())
        ];

        return view('profile.ballance', $data);
    }  

    private static function getPerPage()
    {
        $perPage = request()->per_page ? request()->per_page : \Session::get('ballance_per_page'); 
        if (\Session::get('ballance_per_page') != $perPage or !\Session::get('ballance_per_page')) 
        { 
            \Session::put('ballance_per_page', $perPage);
        } 
        return $perPage;
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
            'year'        => $expiryDate[1] 
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    } 

    public function deleteCreditCard($lang, $id)
    { 
        $card = BankCards::where('id_user', \Auth::user()->id)->whereId($id)->firstOrFail();
        $card->delete();
        return redirect()->back()->with('lk_success', 'Запись успешно удалена');
    }

    public function withdrawFunds($lang, Request $request, WithdrawalService $withdrawalService)
    { 
        try {
            $withdrawalService->validate($request, \Auth::user()->ballance);
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }

        $amount = toFloat($request->price);

        if (setting('moderation_withdrawal') == 'on') 
        {
            $withdraw = WithdrawTips::create([
                'id_user'        => \Auth::user()->id,
                'id_card'        => $request->card,
                'rand'           => generate_id(7),
                'amount'         => $amount-setting('commision_withdrawal'),
                'commision'      => setting('commision_withdrawal') ?: 0,
                'moderation'     => '1',
                'request_status' => 'pending'
            ]); 

            (new Ballance)->setUser(\Auth::user()) 
                          ->setWithdrawId($withdraw->id)
                          ->setPrice($amount)
                          ->off();

            AdminUser::where('type', 'manager')->where('active', 1)->each(function($manager){
                $manager->notify(new NewWithdrawalRequest);
            });

            return \JsonResponse::success([
                'messages' => 'Запрос был отправлен модератору а средства для вывода заморожены'
            ]);
        }
        else
        {
            \DB::beginTransaction(); 
            try {
                $withdrawalService->setIdCard($request->card)
                                  ->setAmount($amount)
                                  ->setUserId(\Auth::user()->id)
                                  ->handle();

                \DB::commit();
                return \JsonResponse::success([
                    'messages' => 'Операция прошла успешно! Денежные средства зачислятся на карту в течении двух банковских дней'
                ]);
            } catch (\Exception $e) {
                \DB::rollback();
                return \JsonResponse::error(['messages' => $e->getMessage()]);
            }  
        }  
    }

    private function validateCreditCardForm($request)
    {
        if (!$request->name or !$request->number or !$request->expiry_date) 
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