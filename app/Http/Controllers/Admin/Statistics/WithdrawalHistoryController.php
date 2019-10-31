<?php

namespace App\Http\Controllers\Admin\Statistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WithdrawTips;  
use App\Models\User;  

class WithdrawalHistoryController extends Controller
{ 
    private $method = 'statistics/withdrawal-history';

    private $folder = 'statistics.withdrawal'; 

    private $redirectRoute = 'admin_withdrawal'; 

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
            'data'    => $this->model->orderByRaw('id desc') 
                                     ->whidrawHistory()
                                     ->with(['user', 'card', 'statusData'])
                                     ->filter()
                                     ->get(),
            'table'   => $this->model->getTable(),
            'method'  => $this->method, 
            'clients' => User::has('withdraw')->orderBy('id', 'desc')->get()
        ];   

        self::closeOpenWithdraw();

        return view('admin.'.$this->folder.'.list', $data);
    }    

    private static function closeOpenWithdraw()
    {
        WithdrawTips::where('open_admin', '1')->update(['open_admin' => 0]); 
    }
}
