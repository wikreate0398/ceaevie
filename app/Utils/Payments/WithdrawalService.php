<?php 

namespace App\Utils\Payments;
 
use App\Utils\Encryption; 
use App\Utils\Ballance; 
use App\Models\User;
use App\Models\Tips; 
use App\Models\BankCards; 
use App\Models\WithdrawTips;

class WithdrawalService
{ 
	protected $card;

	protected $amount;

	protected $crypt;

	protected $payoutService;

	protected $ballanceService;

	protected $user;

    protected $withdrawId;
	
	function __construct(WithdrawTips $tips, Encryption $crypt, VisaPayment $payoutService, Ballance $ballanceService) 
	{
		$this->withdrawTips  = $tips;
		$this->crypt         = $crypt;
		$this->payoutService = $payoutService;
		$this->ballanceService      = $ballanceService;
	}

	public function setIdCard($idCard)
	{ 
		$this->card = BankCards::whereId($idCard)->first();
		return $this;
	}

	public function setAmount($amount)
	{ 
		$this->amount = $amount;
		return $this;
	}

	public function setUserId($idUser)
	{
		$this->user = User::whereId($idUser)->first();
		return $this;
	}

    public function setWithdrawId($id)
    {
        $this->withdrawId = $id;
        return $this;
    }

	private function registerWithdraw()
    {
        if (!$this->withdrawId) 
        {
            $id = $this->withdrawTips->create([
                'id_user'   => $this->user->id,
                'id_card'   => $this->card->id,
                'rand'      => generate_id(7),
                'amount'    => $this->amount-setting('commision_withdrawal'),
                'commision' => setting('commision_withdrawal') ?: 0
            ])->id;
        }
        else
        {
            $id = $this->withdrawId;
        }

        return $this->withdrawTips->whereId($id)->first();
    }

    public function handle()
    { 
    	$withdraw = $this->registerWithdraw(); 
        
        if (!$this->withdrawId) 
        {
            $this->offUserBallance($withdraw);
        }    	 
         
        $this->payoutService->setOrderId($withdraw->rand)
                      ->setAmount(toFloat($withdraw->amount))
                      ->setDescription('Вывод средств официанту ' . $this->user->name)
                      ->setCardCredentials([
                        'name'   => $this->card->name,
                        'number' => $this->crypt->decrypt($this->card->number),
                        'month'  => $this->card->month,
                        'year'   => $this->card->year 
                    ]);

        $payoutResponse = $this->payoutService->payout();   
        
        if (in_array($payoutResponse->success, ['false', 'fail'])) 
        {
            throw new \Exception("В процессе вывода средств возникла ошибка."); 
        }

        $this->saveRequisites($withdraw, $payoutResponse); 
    }

    private function saveRequisites($withdraw, $requisites)
    {
    	$withdraw->id_transaction = $requisites->tranId;
        $withdraw->pan_ref_token  = $requisites->panRefToken ?: '';
        $withdraw->status         = ($requisites->success == 'true') ? 'SUCCESS' : strtoupper($requisites->success);
        $withdraw->save(); 
    }

    private function offUserBallance($withdraw)
    {
        $this->ballanceService->setUser($this->user)
                              ->setWithdrawId($withdraw->id)
                              ->setPrice($withdraw->amount)
                              ->off();
    }

    public function validate($request, $totalAmount)
    {
        if (!$request->price or !$request->card) 
        {
            throw new \Exception(\Constant::get('REQ_FIELDS')); 
        } 

        if ($request->price > $totalAmount) 
        {
            throw new \Exception("На вашем счету нет столько средств.");
        } 

        $minPrice = setting('minimum_withdrawal'); 

        if ($request->price < $minPrice) 
        {
            throw new \Exception('Для вывода неоходимо указать сумму не менее '.setting('minimum_withdrawal').' рубл.'); 
        }

        if (!BankCards::whereId($request->card)->count()) 
        {
            throw new \Exception("Ошибка");
        }

        return $this;
    } 
}