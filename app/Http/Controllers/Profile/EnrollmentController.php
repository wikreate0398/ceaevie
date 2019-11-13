<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Tips; 
use App\Models\ProfileMenu;
  
class EnrollmentController extends Controller
{ 
    public function index()
    {    
        $menu = ProfileMenu::where('route', 'enrollment')->first();
    	$tips = Tips::confirmed()
                    ->where('id_user', \Auth::user()->id)
                    ->filter()
                    ->orderBy('id', 'desc')
                    ->paginate(self::getPerPage());

    	self::closeOpenTips();
        return view('profile.enrollment', compact(['tips', 'menu']));
    }  

    private static function closeOpenTips()
    {
    	Tips::confirmed()->where('id_user', \Auth::user()->id)->where('open', '1')->update(['open' => 0]); 
    }

    private static function getPerPage()
    {
    	$perPage = request()->per_page ? request()->per_page : \Session::get('per_page'); 
    	if (\Session::get('per_page') != $perPage or !\Session::get('per_page')) 
    	{ 
    		\Session::put('per_page', $perPage);
    	} 
    	return $perPage;
    }
}