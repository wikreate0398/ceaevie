<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\User;   
use App\Models\ProfileMenu;   

class MyReferralsController extends Controller
{ 
    public function index()
    {    
        $menu = ProfileMenu::where('route', 'my_referrals')->first(); 
    	$users = User::where('agent_code', \Auth::user()->code)->with('locationUsers')->get();

        $referrals = collect();
        foreach ($users as $user) {
            foreach ($user->locationUsers as $locationUser){
                $referrals->push($locationUser);
            }
            $user->unsetRelation('locationUsers');
            $referrals->push($user);
    	}

        return view('profile.partner.referrals', compact(['referrals', 'menu']));
    }   
}