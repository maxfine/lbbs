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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['bindings']
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'expires' => config('api.rate_limits.sign.expires'),
        'limit' => config('api.rate_limits.sign.limit'),
    ], function($api) {
        $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        $api->post('users', 'UsersController@store')->name('api.users.store');

        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        $api->delete('authorizations/current', 'AuthorizationsController@destory')
            ->name('api.authorizations.destory');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 游客可以访问的接口

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destory')
                ->name('api.topics.destory');

            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');
            $api->delete('replies/{reply}', 'RepliesController@destory')
                ->name('api.replies.destory');

            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');
            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');
            $api->patch('user/notifications/read', 'NotificationsController@read')
                ->name('api.user.notifications.read');
        });

        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');
        $api->get('user/{user}/topics', 'TopicsController@userIndex')
            ->name('api.user.topics.index');

        $api->get('topics/{topic}/{replies}', 'RepliesController@index')
            ->name('api.topics.replies.index');
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');
    });
});

