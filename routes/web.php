<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|  - prefix    : admin
|  - name      : admin.
|  - namespace : Admin
|--------------------------------------------------------------------------
*/

Route::get('/',  'Admin\LoginController@top')->name('index');

Route::prefix('admin')
    ->name('admin.')
    ->namespace('Admin')
    ->group(function () {

        // login control
        Route::get('/login',  'LoginController@index')->name('login.index');
        Route::post('/login', 'LoginController@authenticate')->name('login.authenticate');

        // middleware auth range
        Route::group(['middleware' => 'auth.admin.check'], function () {
            // top control
            Route::get('/home',      'HomeController@index')->name('home.index');

            // calendar control
            Route::get('/calendars', 'CalendarsController@index')->name('calendars.index');

            // schedule control
            Route::get('/schedules/{list_key}', 'SchedulesController@index')->name('schedules.index');

            // race control
            Route::get('/races/denma/{race}',   'RacesController@denma')->name('races.denma');
            Route::get('/races/result/{race}',  'RacesController@result')->name('races.result');
            Route::get('/races/pillar5/{race}', 'RacesController@pillar5')->name('races.pillar5');

            // logout
            Route::get('/logout',    'LoginController@logout')->name('logout');
        });

});
