<?php

/*
|--------------------------------------------------------------------------
| AJAX Routes
|--------------------------------------------------------------------------
|
| Route file to local ajax requests, where they can benefit from
| session state and CSRF protection. Ajax routes are filtered to check if their
| request has seted to XMLHttpRequest through the AjaxRequests middleware and
| receive all the verifications, filtering and protection from the web mapping.
| Ajax routes, by its prefix already receive middleware interception through
| a configuration in the mapAjaxRoutes in the \App\Providers\RouteServiceProvider.
|
 */

Route::post('/send-message/', 'DashboardController@sendPublicMessage');

Route::get('/get-message/', 'FrontController@getAllMessages');


Route::get('/new-user-join-chat/', 'FrontController@doNewUserJoinChat');

Route::get('/new-user-leave-chat/', 'FrontController@doNewUserLeaveChat');