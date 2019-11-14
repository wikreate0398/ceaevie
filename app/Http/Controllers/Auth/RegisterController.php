<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ConfirmRegistration; 

use App\Models\User;
use App\Models\UserType; 
use App\Models\LocationUser;

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
        $this->middleware('guest', ['except' => ['confirmation', 'finishRegistrationForm', 'finishRegistration']]);
    }

    public function showForm()
    {  
        $userTypes = UserType::all();
        return view('auth/registration', compact('userTypes'));
    }

    public function register(Request $request)
    { 
        if(!$request->type or !$request->name or !$request->email or !$request->lastname or !$request->phone or !$request->password or !$request->password_confirmation or ($request->type == 'admin' && !$request->institution_name))
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        } 

        if (!in_array($request->type, UserType::select('type')->get()->pluck('type')->toArray())) 
        {
            return \JsonResponse::error(['messages' => 'Ошибка']);
        }

        try {
            $this->checkPass($request->password, $request->password_confirmation);
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);   
        } 

        if(User::where('email', $request->email)->count())
        {
            return \JsonResponse::error(['messages' => \Constant::get('USER_EXIST')]);
        }

        $confirm_hash = md5(microtime());

        $user = User::create([
            'name'         => $request->name,
            'type'         => $request->type,
            'work_type'    => ($request->type == 'admin') ? 'common_sum' : ''
            'lastname'     => $request->lastname,
            'institution_name' => $request->institution_name ?: '',
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

    private function checkPass($password, $password_confirmation)
    {
        if($password != $password_confirmation)
        {
            throw new \Exception(\Constant::get('PASS_NOT_MATCH')); 
        }

        if(strlen($password) < 8)
        {
            throw new \Exception(\Constant::get('PASS_RESTRICTION'));  
        }
    }

    public function finishRegistrationForm($lang, $hash)
    {
        $user = LocationUser::where('hash', $hash)->with('user')->firstOrFail();
        return view('auth.finish_registration', compact('user'));
    } 

    public function finishRegistration(Request $request)
    {

        if(!$request->name or !$request->lastname or !$request->phone or !$request->password or !$request->password_confirmation)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }  

        $userLocation = LocationUser::where('hash', $request->hash)->with('user')->first();

        if (!$userLocation) 
        {
            return \JsonResponse::error(['messages' => 'Ошибка']);
        }

        try {
            $this->checkPass($request->password, $request->password_confirmation);
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);   
        }  

        User::whereId($userLocation->id_user)->update([
            'name'         => $request->name, 
            'lastname'     => $request->lastname, 
            'phone'        => $request->phone,  
            'password'     => bcrypt($request->password),
            'lang'         => lang(),
            'active'       => 1,
            'confirm'      => 1
        ]);

        $userLocation->hash = '';
        $userLocation->status = 'confirmed';
        $userLocation->save(); 

        return \JsonResponse::success([
            'messages' => 'Вы успешно завершили регистрацию. Теперь вы можете перейти на страницу <a href="'. route('show_login', ['lang' => lang()]) .'">авторизации</a>'
        ]);
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