<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\MakeQuestion;
use App\Models\ContactUs;
use App\Models\ProfileMenu;

class ContactController extends Controller
{ 
    public function index()
    { 
        $data = [
            'menu' => ProfileMenu::where('route', 'contact')->first()
        ];

        return view('profile.contact', $data);
    }  

    public function send($lang, Request $request)
    {
        if (!$request->name or !$request->phone or !$request->message) 
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        ContactUs::create([
        	'name'    => $request->name,
        	'phone'   => $request->phone,
        	'message' => $request->message,
        	'id_user' => \Auth::user()->id
        ]);

        Mail::to(\Constant::get('EMAIL'))->send(new MakeQuestion($request->name, $request->phone, $request->message));

        return \JsonResponse::success([
            'messages' => 'Ваше сообщение успешно отпарвлено. Наш менеджер свяжется с вами в близжайшее время'
        ]);
    }
}