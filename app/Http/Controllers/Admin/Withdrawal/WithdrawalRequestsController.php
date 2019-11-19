<?php

namespace App\Http\Controllers\Admin\Withdrawal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ManageWithdrawalRequest;
use App\Utils\Payments\WithdrawalService; 
use App\Utils\Ballance;  
use App\Models\WithdrawTips;  
use App\Models\WithdrawalRequestStatus;  
use App\Models\User;  

class WithdrawalRequestsController extends Controller
{ 
    private $method = 'withdrawal/requests';

    private $folder = 'withdrawal.requests'; 

    private $redirectRoute = 'admin_withdrawal_requests'; 

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new WithdrawTips;
        $this->method = config('admin.path') . '/' . $this->method;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {  
        $data = [
            'data'     => $this->model->orderByRaw('id desc')
                                      ->where('moderation', '1')
                                      ->with(['user', 'card', 'statusData'])
                                      ->filter()
                                      ->get(),
            'table'    => $this->model->getTable(),
            'method'   => $this->method, 
            'clients'  => User::has('withdraw_requests')->orderBy('id', 'desc')->get(),
            'statuses' => WithdrawalRequestStatus::orderByRaw('page_up asc, id desc')->get()
        ];   

        self::closeOpenRequests();

        return view('admin.'.$this->folder.'.list', $data);
    }    

    public function changeRequestStatus($id, $status, WithdrawalService $withdrawalService)
    {
        $statusData = WithdrawalRequestStatus::where('define', $status)->firstOrFail();
        $withdraw   = WithdrawTips::whereId($id)
                                  ->where('request_status', 'pending')
                                  ->where('moderation', '1')
                                  ->firstOrFail();

        if ($status == 'rejected') 
        {
            $this->rejectedRequest($withdraw);
        }
        else
        {   
            \DB::beginTransaction(); 
            try {
                $this->confirmedRequest($withdraw, $withdrawalService);
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback(); 
                return redirect()->back()->with('admin_err_flash_message', $e->getMessage()); 
            } 
        }
        
        $withdraw->id_manager     = \Auth::user()->id;
        $withdraw->response_at    = date('Y-m-d H:i:s');
        $withdraw->request_status = $status;
        $withdraw->save();

        return redirect()->back()->with('admin_flash_message', 'Статус успешно изменен');
    }

    private function rejectedRequest($withdraw)
    {
        // возвращаем замороженные средства
        (new Ballance)->setUser($withdraw->user) 
                      ->setPrice($withdraw->amount + $withdraw->commision)
                      ->replenish(); 

        // отправляем уведомление
        $withdraw->user->notify(new ManageWithdrawalRequest('withdraw_rejected', $withdraw->amount, $withdraw->rand));
    }

    private function confirmedRequest($withdraw, $withdrawalService)
    {  
        $withdrawalService->setIdCard($withdraw->id_card)
                          ->setWithdrawId($withdraw->id)
                          ->setAmount($withdraw->amount)
                          ->setUserId($withdraw->id_user)
                          ->handle(); 
    }

    private static function closeOpenRequests()
    {
        WithdrawTips::where('open', '1')->where('moderation', '1')->update(['open' => 0]); 
    }
}
