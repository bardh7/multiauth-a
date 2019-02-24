<?php


Route::prefix(multiauth()->prefix())->group(function () {

    // Login
    Route::get('login', 'LoginController@showLoginForm')->name(multiauth()->routeName('login'));
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name(multiauth()->routeName('logout'));

    // Register
    if (multiauth()->allowRgistration()) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name(multiauth()->routeName('register'));
        Route::post('register', 'RegisterController@register');
    }

    // Passwords
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name(multiauth()->routeName('password.email'));
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name(multiauth()->routeName('password.request'));
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name(multiauth()->routeName('password.reset'));

});
