<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MakeQuestion;

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

    public function questions($lang, Request $request)
    {
        if (!$request->name or !$request->phone or !$request->message) 
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        Mail::to(\Constant::get('EMAIL'))->send(new MakeQuestion($request->name, $request->phone, $request->message));

        return \JsonResponse::success([
            'messages' => 'Ваше сообщение успешно отпарвлено. Нам менеджер свяжется с вами в близжайшее время'
        ]);
    }
}