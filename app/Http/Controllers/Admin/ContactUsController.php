<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactUs; 

class ContactUsController extends Controller
{
    private $method = 'oficiant-profile/contact-us';

    private $folder = 'contact_us';

    private $redirectRoute = 'admin_contact_us'; 
    
    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new ContactUs;
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
            'data'   => $this->model->orderByRaw('id desc')->with('user')->get(),
            'table'  => $this->model->getTable(),
            'method' => $this->method
        ]; 

        self::closeOpenMessages();

        return view('admin.'.$this->folder.'.list', $data);
    }   

    private static function closeOpenMessages()
    {
        ContactUs::where('open', '1')->update(['open' => 0]); 
    }
}
