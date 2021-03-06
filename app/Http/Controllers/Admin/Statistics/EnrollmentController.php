<?php

namespace App\Http\Controllers\Admin\Statistics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tips;  
use App\Models\User;  

class EnrollmentController extends Controller
{ 
    private $method = 'statistics/enrollment';

    private $folder = 'statistics.enrollment'; 

    private $redirectRoute = 'admin_enrollment'; 

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
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
                                     ->with(['user', 'location', 'qr_code', 'payment'])
                                     ->filter()
                                     ->get(),
            'table'   => $this->model->getTable(),
            'method'  => $this->method, 
            'clients' => User::has('tips')->orderBy('id', 'desc')->get()
        ];   

        self::closeOpenTips();

        return view('admin.'.$this->folder.'.list', $data);
    }    

    public function export(Request $request)
    {   
        $data = $this->model->orderByRaw('id desc')
                                     ->confirmed()
                                     ->with(['user', 'location', 'qr_code', 'payment', 'payment_service_data'])
                                     ->filter()
                                     ->get(); 

        return \Excel::download(
            new \App\Exports\EnrollmentExport($data), 
            'Enrollment.xlsx'
          ); 
    }

    private static function closeOpenTips()
    {
        Tips::where('open_admin', '1')->update(['open_admin' => 0]); 
    }
}
