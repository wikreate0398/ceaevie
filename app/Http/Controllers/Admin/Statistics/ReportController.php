<?php

namespace App\Http\Controllers\Admin\Statistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tips;  
use App\Models\EnrollmentPercents;
use App\Models\TipPercents;
use App\Models\User;  

class ReportController extends Controller
{ 
    private $method = 'statistics/report';

    private $folder = 'statistics.report'; 

    private $redirectRoute = 'admin_report'; 

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {

        if (!empty(request()->test)) {
            TipPercents::truncate();
            Tips::all()->each(function($tip){
                $tipId = $tip->id;
                EnrollmentPercents::all()->each(function($percent) use($tipId){
                    TipPercents::insert([
                        'id_tip'     => $tipId,
                        'id_percent' => $percent->id,
                        'percent'    => $percent->percent
                    ]);
                });
            });
        }

        $this->model  = new Tips;
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
                                     ->confirmed()
                                     ->with(['user', 'qr_code', 'payment', 'percents'])
                                     ->filter()
                                     ->get(),
            'percents' => EnrollmentPercents::orderBy('percent', 'asc')->get(),
            'table'   => $this->model->getTable(),
            'method'  => $this->method, 
            'clients' => User::has('withdraw')->orderBy('id', 'desc')->get()
        ];    

        return view('admin.'.$this->folder.'.list', $data);
    }  
}
