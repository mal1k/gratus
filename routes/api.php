<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\Api\Admin\{
    OrganizationController,
    ReceiverController,
    SearchController,
    StatsController,
    UserController,
    EmailController,
    OrganizationGroupsController,
    PushsController,
    ScheduleController,
    TipperAdminController,
    TransactionsAdminController
};
use App\Http\Controllers\Api\Mobile\ApiController;
use App\Models\User;

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

/**
 * API routes for cells
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/users', function () {
        return User::all();
     });
});

Route::prefix('mobile')->group(function() {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('isEmailConfirmed', [AuthController::class, 'isEmailConfirmed']);
    Route::post('transaction/create', [AuthController::class, 'createTransaction']);
    Route::get('getInfo', [AuthController::class, 'getInfo']);
    Route::post('updateUserInfo', [AuthController::class, 'updateUserInfo']);
    Route::post('setFirebaseToken', [AuthController::class, 'setFirebaseToken']);
    Route::post('forgotPass', [AuthController::class, 'forgotPass']);
    Route::post('forgotPassCheck', [AuthController::class, 'forgotPassCheckCode']);
    Route::post('forgotPassConfirm', [AuthController::class, 'forgotPassConfirmAPI']);
    Route::post('userTransactions', [AuthController::class, 'getUserTransactions']);
    Route::get('groupList', [ApiController::class, 'groupList']);
    Route::get('getNotifications', [ApiController::class, 'getNotifications']);

});

/**
 * API routes for the Superadmin
 */
Route::middleware(['auth:sanctum', 'ajax'])->prefix('manager')->group(function() {

    Route::post('/get-stats', StatsController::class);

    Route::prefix('search')->group(function() {

        Route::post('/organizations', [SearchController::class, 'organizations']);

        Route::post('/receivers', [SearchController::class, 'receivers']);

        Route::post('/users', [SearchController::class, 'users']);

        Route::post('/mails', [SearchController::class, 'mails']);

        Route::post('/push-notifications', [SearchController::class, 'pushs']);

        Route::post('/tippers', [SearchController::class, 'tippers']);

        Route::post('/transactions', [SearchController::class, 'transactions']);

        Route::post('/organizationgroups/{id}', [SearchController::class, 'organizationgroups']);

        Route::post('/schedule', [SearchController::class, 'schedule']);

    });

    Route::apiResource('receivers', ReceiverController::class);

    Route::apiResource('transactions', TransactionsAdminController::class);

    Route::apiResource('organizations', OrganizationController::class);

    Route::apiResource('organization/groups', OrganizationGroupsController::class);

    Route::apiResource('schedule', ScheduleController::class);

    Route::apiResource('users', UserController::class);

    Route::apiResource('mails', EmailController::class);

    Route::apiResource('push-notifications', PushsController::class);

    Route::apiResource('tippers', TipperAdminController::class);

});
