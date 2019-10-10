<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('page-404', function(){
	return response()->view('errors.404', [], 404);
})->name('404');

$adminPath = config('admin.path');
 
Route::get($adminPath . '/login', 'Admin\LoginController@showLoginForm', ['guard' => 'admin'])->name('admin_login');
Route::post($adminPath . '/login', 'Admin\LoginController@login', ['guard' => 'admin'])->name('admin_run_login'); 

Route::group(['prefix' => $adminPath, 'namespace' => 'Admin', 'middleware' => ['admin', 'web']], function() { 
	Route::get('/', function(){
		return redirect()->route('admin_menu');
	});
	
	Route::group(['prefix' => 'menu'], function() { 
		Route::get('/', 'MenuController@show')->name('admin_menu');  
		Route::get('{id}/edit', 'MenuController@showeditForm');  
		Route::post('create', 'MenuController@create'); 
		Route::post('{id}/update', 'MenuController@update'); 
	});

    Route::group(['prefix' => 'constants'], function() {
        Route::get('/', 'ConstantsController@show')->name('admin_constants');
        Route::post('create', 'ConstantsController@create');
        Route::post('createConstant', 'ConstantsController@createConstant');
    });

	Route::group(['prefix' => 'languages'], function() { 
		Route::get('/', 'LanuagesController@show')->name('admin_languages');  
		Route::get('{id}/edit', 'LanuagesController@showeditForm'); 
		Route::get('add', 'LanuagesController@showAddForm');
		Route::post('create', 'LanuagesController@create'); 
		Route::post('{id}/update', 'LanuagesController@update'); 
	}); 

	Route::group(['prefix' => 'how-it-works'], function() { 
		Route::get('/', 'HowItWorksController@show')->name('admin_hit');  
		Route::get('{id}/edit', 'HowItWorksController@showeditForm');  
		Route::post('create', 'HowItWorksController@create'); 
		Route::post('{id}/update', 'HowItWorksController@update'); 
	});  

    Route::group(['prefix' => 'whom'], function() { 
        Route::get('/', 'WhomController@show')->name('admin_whom');  
        Route::get('{id}/edit', 'WhomController@showeditForm');  
        Route::post('create', 'WhomController@create'); 
        Route::post('{id}/update', 'WhomController@update'); 
    });  

    Route::group(['prefix' => 'payment-types'], function() { 
        Route::get('/', 'PaymentTypesController@show')->name('admin_payment_types');  
        Route::get('{id}/edit', 'PaymentTypesController@showeditForm');  
        Route::post('create', 'PaymentTypesController@create'); 
        Route::post('{id}/update', 'PaymentTypesController@update'); 
    });  

    Route::group(['prefix' => 'advantages'], function() { 
        Route::get('/', 'AdvantagesController@show')->name('admin_advantages');  
        Route::get('{id}/edit', 'AdvantagesController@showeditForm');  
        Route::post('create', 'AdvantagesController@create'); 
        Route::post('{id}/update', 'AdvantagesController@update'); 
    });  

    Route::group(['prefix' => 'email-templates'], function() {
        Route::get('/', 'EmailTemplatesController@show')->name('admin_email_templates');
        Route::get('{id}/edit', 'EmailTemplatesController@showeditForm'); 
        Route::post('{id}/update', 'EmailTemplatesController@update');
    });

    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', 'SettingsController@show')->name('admin_settings');
        Route::post('save', 'SettingsController@save');
    });
  
    Route::group(['prefix' => 'clients'], function() {
        Route::get('/', 'ClientsController@show')->name('admin_clients');
        Route::get('{id}/edit', 'ClientsController@showeditForm');
        Route::get('add', 'ClientsController@showAddForm');
        Route::post('create', 'ClientsController@create');
        Route::post('{id}/update', 'ClientsController@update');
        Route::post('{id}/send-letter', 'ClientsController@sendLetter');
        Route::get('{id}/autologin', 'ClientsController@autologin'); 
    });

	Route::group(['prefix' => 'profile'], function() { 
		Route::get('/', 'ProfileController@showForm')->name('profile');
		Route::post('edit', 'ProfileController@edit');
		Route::post('addNewUser', 'ProfileController@addNewUser');
        Route::get('/logs-report/{id_user}', 'ProfileController@showLogsReport');
	}); 
  
	Route::group(['prefix' => 'user', 'namespace' => 'Users',], function() { 
		Route::post('fastRegister', 'SiteUser@fastRegister');  
	});

	Route::group(['prefix' => 'ajax'], function() {  
		Route::post('depth-sort', 'AjaxController@depthSort')->name('depth_sort');
		Route::post('viewElement', 'AjaxController@viewElement')->name('viewElement'); 
		Route::post('deleteElement', 'AjaxController@deleteElement')->name('deleteElement');   
		Route::post('deleteImg', 'AjaxController@deleteImg')->name('deleteImg');
        Route::post('sortElement', 'AjaxController@sortElement')->name('sortElement');
	}); 

	Route::get('logout', 'LoginController@logout')->name('admin_logout'); 
});

Route::get('/', 'HomeController@index')->middleware(['lang', 'web']);

Route::post('messages', function(\Illuminate\Http\Request $request){ 
    \App\Events\ItemAdded::dispatch($request->body);
});

Route::group(['prefix' => '{lang}', 'middleware' => ['lang', 'web']], function() {
    Route::get('/', 'HomeController@index'); 
    Route::get('/payment', 'PaymentController@index');
  
    Route::group(['middlewars' => 'guest'], function(){
        Route::post('register', 'Auth\RegisterController@register')->name('register');
        Route::get('finish-registration', 'Auth\RegisterController@finish_registration')->name('finish_registration');
        Route::get('registration-confirm/{confirmation_hash}', 'Auth\RegisterController@confirmation')->name('registration_confirm');
        Route::post('login', 'Auth\LoginController@login')->name('login');
        Route::post('reset-password', 'Auth\ForgotPasswordController@sendResetPassword')->name('send_reset_pass');

        Route::group(['prefix' => 'profile', 'namespace' => 'User', 'middleware' => 'web_auth'], function() {
            Route::get('personal-data', 'ProfileController@personalData')->name('personal_data');
            Route::post('personal-data', 'ProfileController@savePersonalData')->name('save_personal_data');

            Route::get('change-password', 'ProfileController@changePass')->name('change_pass');
            Route::post('change-password', 'ProfileController@savePassword')->name('save_new_password');

            Route::group(['prefix' => 'register'], function() {
                Route::get('offers-placed', 'RegisterController@offersPlaced')->name('offers_placed');
                Route::get('transactions', 'RegisterController@transactions')->name('transactions');
                Route::get('orders', 'RegisterController@orders')->name('orders');
               // Route::get('bids', 'RegisterController@bids')->name('register_bids');
            });
        });
    });
      
    /*
     * Routes for authorized users
     */
    Route::group(['middleware' => ['web_auth']], function(){
        Route::get('logout', function(){
            Auth::guard('web')->logout();
            return  redirect('/');
        })->name('logout');
    });


    /*
     * If not exist route
     */
    Route::get('{any}', 'HomeController@page');
});



//Auth::routes();

