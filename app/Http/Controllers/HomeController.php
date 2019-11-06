<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MakeQuestion;

use App\Models\PaymentType;
use App\Models\HowItWork;
use App\Models\Whom;
use App\Models\Advantage;
use App\Models\QrCode;
use App\Models\ContactUs;

class HomeController extends Controller
{ 
    public function index()
    {     
        $payments   = PaymentType::orderByPageUp()->visible()->get();
        $howWork    = HowItWork::orderByPageUp()->visible()->get();
        $whom       = Whom::orderByPageUp()->visible()->get(); 
        $advantages = Advantage::orderByPageUp()->visible()->get();
        return view('public/home', compact('payments', 'howWork', 'whom', 'advantages'));
    } 

    public function page()
    { 
        $data = \Pages::pageData();
        if (!$data) 
        {
            abort('404');
        }
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

        ContactUs::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'message' => $request->message,
            'id_user' => \Auth::check() ? \Auth::user()->id : ''
        ]);

        Mail::to(\Constant::get('EMAIL'))->send(new MakeQuestion($request->name, $request->phone, $request->message));

        return \JsonResponse::success([
            'messages' => 'Ваше сообщение успешно отпарвлено. Наш менеджер свяжется с вами в близжайшее время'
        ]);
    } 

    public function giveThanks($lang, Request $request)
    {
        try {
            if (!$request->code or !$request->price) 
            {
                throw new \Exception(\Constant::get('REQ_FIELDS')); 
            }

            $code  = prepareCode($request->code);  
            if (!QrCode::where('code', $code)->count()) 
            {
                throw new \Exception('Официант не найден'); 
            } 

            return \JsonResponse::success([
                'redirect' => route('payment', ['lang' => lang(), 'code' => $code]) . '?price=' . $request->price
            ]);

        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }  
    }
}