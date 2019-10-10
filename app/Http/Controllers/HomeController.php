<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\PaymentType;
use App\Models\HowItWork;
use App\Models\Whom;
use App\Models\Advantage;

class HomeController extends Controller
{ 
    public function index()
    {
        $payments   = PaymentType::orderByPageUp()->visible()->get();
        $howWork    = HowItWork::orderByPageUp()->visible()->get();
        $whom       = Whom::orderByPageUp()->visible()->get();
        $whom       = Whom::orderByPageUp()->visible()->get();
        $advantages = Advantage::orderByPageUp()->visible()->get();
        return view('public/home', compact('payments', 'howWork', 'whom', 'advantages'));
    } 

    public function page()
    {
        $data = \Pages::pageData();
        return view('public/page', [
            'data'   => $data,
        ]);
    } 
}