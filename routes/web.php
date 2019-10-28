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

    Route::group(['prefix' => 'oficiant-profile'], function() { 
        Route::group(['prefix' => 'backgrounds'], function() { 
            Route::get('/', 'BackgroundsController@show')->name('admin_backgrounds');  
            Route::get('{id}/edit', 'BackgroundsController@showeditForm');  
            Route::post('create', 'BackgroundsController@create'); 
            Route::post('{id}/update', 'BackgroundsController@update'); 
        });  

        Route::group(['prefix' => 'contact-us'], function() { 
            Route::get('/', 'ContactUsController@show')->name('admin_contact_us');  
            Route::get('{id}/edit', 'ContactUsController@showeditForm');  
            Route::post('create', 'ContactUsController@create'); 
            Route::post('{id}/update', 'ContactUsController@update'); 
        });    
    }); 

    Route::group(['prefix' => 'statistics', 'namespace' => 'Statistics'], function() { 
        Route::group(['prefix' => 'enrollment'], function() { 
            Route::get('/', 'EnrollmentController@show')->name('admin_enrollment');  
            Route::get('{id}/edit', 'EnrollmentController@showeditForm');  
            Route::post('create', 'EnrollmentController@create'); 
            Route::post('{id}/update', 'EnrollmentController@update'); 
        });    
    });

	Route::group(['prefix' => 'profile'], function() { 
        Route::get('/', 'ProfileController@showForm')->name('profile');
        Route::get('{id}/edit-user', 'ProfileController@editAdminUser');
        Route::post('{id}/update-admin-user', 'ProfileController@updateAdminUser');
        Route::post('edit', 'ProfileController@edit');
        Route::post('addNewUser', 'ProfileController@addNewUser');
        Route::get('/logs-report/{id_user}', 'ProfileController@showLogsReport');
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
  
Route::group(['prefix' => '{lang}', 'middleware' => ['lang', 'web']], function() {
    
    Route::get('/', 'HomeController@index');
    Route::post('questions', 'HomeController@questions')->name('questions'); 
    Route::post('set-code', 'Pay\PaymentController@setСode')->name('set_code_home');
    Route::post('give-thanks', 'HomeController@giveThanks')->name('give_thanks');
    
    Route::group(['middlewars' => 'guest'], function(){
        Route::get('registration', 'Auth\RegisterController@showForm')->name('registration');
        Route::post('register', 'Auth\RegisterController@register')->name('register');
        Route::get('finish-registration', 'Auth\RegisterController@finish_registration')->name('finish_registration');
        Route::get('registration-confirm/{confirmation_hash}', 'Auth\RegisterController@confirmation')->name('registration_confirm');

        Route::post('login', 'Auth\LoginController@login')->name('login');
        Route::get('login', 'Auth\LoginController@showLogin')->name('show_login');
        Route::post('reset-password', 'Auth\ForgotPasswordController@sendResetPassword')->name('send_reset_pass');

        Route::group(['prefix' => 'profile', 'namespace' => 'Profile', 'middleware' => 'web_auth'], function() {

            Route::group(['prefix' => 'account'], function() {
                Route::get('/', 'AccountController@index')->name('account');
                Route::post('edit-userdata', 'AccountController@edit')->name('edit_userdata');
                Route::post('change-password', 'AccountController@changePassword')->name('change_password'); 
                Route::post('save-avatar', 'AccountController@saveAvatar')->name('save_avatar');  
            }); 

            Route::group(['prefix' => 'contact-us'], function() {
                Route::get('/', 'ContactController@index')->name('contact');
                Route::post('send', 'ContactController@send')->name('send_contact_us'); 
            }); 

            Route::group(['prefix' => 'workspace'], function() {
                Route::get('/', 'WorkspaceController@index')->name('workspace');
                Route::post('add-qr', 'WorkspaceController@addQrCode')->name('add_qr'); 
                Route::get('delete-qr/{id}', 'WorkspaceController@deleteQrCode')->name('delete_qr'); 
            }); 

            Route::group(['prefix' => 'ballance'], function() {
                Route::get('/', 'BallanceController@index')->name('ballance'); 
                Route::post('add-credit-card', 'BallanceController@addCreditCard')->name('add_card');  
                Route::post('withdraw-funds', 'BallanceController@withdrawFunds')->name('withdraw_funds');  
                Route::get('delete-credit-card/{id}', 'BallanceController@deleteCreditCard')->name('delete_card');  
            });  
             
            Route::get('enrollment', 'EnrollmentController@index')->name('enrollment');
             
             
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

