<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Notifications\UploadVerificationFile;
use App\Notifications\ChangeInvitationStatus; 

use App\Utils\UploadImage;
use App\Utils\QrCodeGenerator;

use App\Models\User;
use App\Models\IdentificationFiles;
use App\Models\AdminUser;
use App\Models\ProfileMenu;
use App\Models\LocationUser;
use App\Models\QrCode;

class AccountController extends Controller
{ 
    public function index()
    { 
        $data = [
            'menu'        => ProfileMenu::where('route', 'account')->first(),
            'invitations' => LocationUser::where('status', 'pending')
                                         ->with('location')
                                         ->where('id_user', \Auth::user()->id) 
                                         ->get()
        ];

        return view('profile.account', $data);
    }  

    public function invitationResponse($lang, Request $request, QrCodeGenerator $qrGenerator)
    { 
        $invitation = LocationUser::where('status', 'pending')
                                   ->with('location')
                                   ->where('id_user', \Auth::user()->id) 
                                   ->where('id', $request->id) 
                                   ->first();

        if ($invitation->status != 'pending') die();

        if ($request->status == 'confirmed') 
        { 
            if (QrCode::where('id_user', \Auth::user()->id)->count() == 3) 
            {
                return \JsonResponse::error(['messages' => 'Что бы принять приглашение Кол-во qr кодов должно быть не более 2']);
            } 

            $qrImage = $qrGenerator->generateCode()
                                   ->genereateImage();

            QrCode::create([
                'id_user'          => $invitation->id_user,
                'id_location'      => $invitation->id_location,
                'card_signature'   => $invitation->card_signature,
                'institution_name' => $invitation->location->institution_name,
                'id_background'    => 1,
                'code'             => $qrGenerator->getCode(),
                'qr_code'          => $qrImage
            ]);
        }

        $invitation->status = $request->status;
        $invitation->save();

        $invitation->location->notify(new ChangeInvitationStatus($invitation->user, $request->status));
 
        if ($request->status == 'confirmed') 
        {
            $msg = 'Приглашение успешно подтвержденно. Мы сгенерировали вам новый QR код для данного заведения.';
        }
        else
        {
            $msg = 'Приглашение успешно отклонено';
        }

        return \JsonResponse::success(['messages' => $msg]);
    }

    public function edit(Request $request){
    	if(!$request->name or !$request->email or !$request->lastname or !$request->phone)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if(User::withTrashed()->whereEmail($request->email)->where('id', '<>', \Auth::user()->id)->count())
        {
            return \JsonResponse::error(['messages' => \Constant::get('USER_EXIST')]);
        }

        if (\Auth::user()->type == 'admin' && empty($request->self_percent)) 
        {
            return \JsonResponse::error(['messages' => 'Укажите процент']);
        }

        User::whereId(\Auth::user()->id)->update([
        	'name'     => $request->name,
        	'lastname' => $request->lastname,
        	'phone'    => $request->phone,
        	'email'    => $request->email,
        	'payment_signature' => $request->payment_signature,
            'work_type'         => $request->work_type ?: null,
            'self_percent'      => @$request->self_percent ?: 0
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    }

    public function changePassword(Request $request)
    {
        if(!$request->old_password or !$request->new_password or !$request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if (!\Hash::check($request->old_password, \Auth::user()->password)) 
        {
        	return \JsonResponse::error(['messages' => 'Старый пароль не верный']);
        }

        if($request->new_password != $request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_NOT_MATCH')]);
        }

        if(strlen($request->new_password) < 8)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_RESTRICTION')]);
        }

        User::whereId(\Auth::user()->id)->update([
            'password' => bcrypt($request->new_password),
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('PASS_SAVED'), 'reload' => true]);
    }

    public function saveAvatar(Request $request)
    {
        if (empty($request->avatar)) 
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Изображение не выбрано']); 
        }

        $filename = 'user-' . \Auth::user()->id . '-' . md5(microtime()) . '.png';
        $avatarImagePath = public_path() . '/uploads/clients/' . $filename;  
        uploadBase64($request->avatar, $avatarImagePath); 
        
        User::where('id', \Auth::user()->id)->
          update([ 
            'image'  => $filename 
        ]); 

        return \App\Utils\JsonResponse::success();
    }

    public function uploadVerificationFile($lang, Request $request)
    {   
        try {
            $uploadImage = new UploadImage;
            $files       = $uploadImage->setExtensions('jpeg,jpg,png')
                                       ->setSize(5000)
                                       ->multipleUpload('files', 'clients');

            if (in_array(\Auth::user()->verification_status, ['confirm', 'pending'])) 
            {
                throw new \Exception("Ошибка"); 
            }

            foreach ($files as $key => $file) 
            {
                IdentificationFiles::create([
                    'id_user' => \Auth::id(),
                    'file'    => $file
                ]);
            }

            User::where('id', \Auth::id())->
              update([  
                'verification_status' => 'pending'
            ]); 
     
            AdminUser::where('active', 1)->each(function($user){
                $user->notify(new UploadVerificationFile(User::whereId(\Auth::user()->id)->first()));
            });

            return \App\Utils\JsonResponse::success(['reload' => true, 'messages' => 'Выши документ успешно сохранился. В скором времени наш менеджер приступит к его обработки']);
        } catch (\Exception $e) { 
            return \App\Utils\JsonResponse::error(['messages' => $e->getMessage()]); 
        }  
    }
}