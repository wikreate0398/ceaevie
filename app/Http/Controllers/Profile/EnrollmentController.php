<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Models\Tips; 
  
class EnrollmentController extends Controller
{ 
    public function index()
    {    
    	$tips = Tips::confirmed()->filter()->orderBy('id', 'desc')->paginate(self::getPerPage()); 
    	self::closeOpenTips();
        return view('profile.enrollment', compact('tips'));
    }  

    private static function closeOpenTips()
    {
    	Tips::confirmed()->where('open', '1')->update(['open' => 0]); 
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