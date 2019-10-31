<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentLogResponse; 

class PaymentsLogController extends Controller
{
     
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new PaymentLogResponse; 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {  
        $data = [
            'data'  => $this->model->orderByRaw('id desc')->get(),
            'table' => $this->model->getTable(),
        ]; 

        return view('admin.logs.list', $data);
    }   
}
