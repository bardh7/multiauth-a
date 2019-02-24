<?php

Route::prefix(multiauth()->prefix())->group(function () {

    // Login
    Route::get('login', 'LoginController@showLoginForm')->name('multiauth.login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('multiauth.logout');

    // Register
    if (multiauth()->allowRgistration()) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name('multiauth.register');
        Route::post('register', 'RegisterController@register');
    }

    // Passwords
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('multiauth.password.email');
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('multiauth.password.request');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('multiauth.password.reset');

});
