<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Notifications\ConfirmRegistration;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'confirmation']);
    }

    public function showForm()
    {
        return view('auth/registration');
    }

    public function register(Request $request)
    {
        $input = $request->all();

        if(!$request->name or !$request->email or !$request->lastname or !$request->phone or !$request->password or !$request->password_confirmation)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if($request->password != $request->password_confirmation)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_NOT_MATCH')]);
        }

        if(strlen($request->password) < 8)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_RESTRICTION')]);
        }

        if(User::where('email', $request->email)->count())
        {
            return \JsonResponse::error(['messages' => \Constant::get('USER_EXIST')]);
        }

        $confirm_hash = md5(microtime());

        $user = User::create([
            'name'         => $request->name,
            'lastname'     => $request->lastname,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'confirm_hash' => $confirm_hash,
            'password'     => bcrypt($request->password),
            'lang'         => lang(),
            'rand'         => generate_id(7)
        ]);

        $user->notify(new ConfirmRegistration($confirm_hash, lang()));
        return \JsonResponse::success([
            'messages' => htmlspecialchars_decode(\Constant::get('REG_SUCCESS'))
        ]);
    }   

    public function finish_registration(Request $request)
    {
        if (\Session::has('reg'))
        {
            $messgae = \Session::get('reg') ;
            \Session::forget('reg');
            return view('auth.finish_registration', compact('messgae'));
        }
        return redirect('/');
    }

    public function confirmation($lang, $confirmation_hash)
    { 
        $user = User::where('confirm_hash', $confirmation_hash)->first();

        if(!$user) abort('404');

        if (empty($user->active)) {
            User::where('id', $user->id)
                ->update(['active' => 1, 'confirm' => 1]);

            if (Auth::check())
            {
                Auth::guard('web')->logout();
            }
            Auth::guard('web')->login($user);

            return redirect()->route('workspace', ['lang' => lang()])->with('flash_message', trans('auth.success_login'));
        }

        return view('auth.confirmation', compact('user'));
    }
}