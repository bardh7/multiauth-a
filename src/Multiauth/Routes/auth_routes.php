<?php


Route::prefix(vega_auth()->guard()->prefix())->group(function () {

    // Login
    Route::get('login', 'LoginController@showLoginForm')->name('vega.auth.login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('vega.auth.logout');

    // Register
    Route::get('register', 'RegisterController@showRegistrationForm')->name('vega.auth.register');
    Route::post('register', 'RegisterController@register');

    // Passwords
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('vega.auth.password.email');
    Route::post('password/reset', 'ResetPasswordController@reset');
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('vega.auth.password.request');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('vega.auth.password.reset');
});
