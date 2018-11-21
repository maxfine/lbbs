<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', [
    'namespace' => 'APP\Http\Controllers\Api'
], function($api) {
    $api->group([
    ], function($api) {
        $api->post('captchas', 'Api\CaptchasController@store');
        $api->post('verificationCodes', 'Api\VerificationCodesController@store');
        $api->post('users', 'Api\UsersController@store');
    });
});

