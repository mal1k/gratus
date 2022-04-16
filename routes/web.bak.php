<?php

use App\Http\Controllers\Admin\AuthOrganizationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Api\Admin\EmailController;
use App\Http\Controllers\Api\Admin\MotivationWordsController;
use App\Http\Controllers\Api\Admin\NFCAccessController;
use App\Http\Controllers\Api\Admin\OrganizationController;
use App\Http\Controllers\Api\Admin\PushsController;
use App\Http\Controllers\Api\Admin\ReceiverController;
use App\Http\Controllers\Api\Admin\ScheduleController;
use App\Http\Controllers\Api\Admin\TipperController;
use App\Http\Controllers\Api\Admin\TransactionsController;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Models\Organization;
use App\Models\organizationgroups;
use App\Models\Receiver;
use App\Models\Tipper;
use App\Models\User;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('forgotPass/{token}', [AuthController::class, 'forgotPassView']);
Route::get('/app/forgotPass/{token}', [AuthController::class, 'forgotPassApp']);
Route::post('forgotPassConfirm', [AuthController::class, 'forgotPassConfirm'])->name('forgotPassConfirm');
Route::get('/update/nfc', function (Request $request)
{
    $receiver = $_GET['receiver'];
    $nfc = $_GET['nfc'];

    $receiver_info = Receiver::where(['email' => $receiver])->first();

    if ( isset($receiver_info) )
    $status = Receiver::where(['email' => $receiver])->update(['nfc_chip' => $nfc]);

    return redirect()->to('/manager/receivers');
});

Route::get('/verificate-account/{code}', [AuthController::class, 'verificateEmail']);

# organization guest
    Route::middleware('guest:organization')->prefix('organization')->group(function() {
        Route::get('{org}/login', [AuthOrganizationController::class, 'loginPage']);
        Route::post('{org}/login', [AuthOrganizationController::class, 'login']);

    # receivers
      // invite
        Route::get('{name}/receivers/invite/{id}', [ReceiverController::class, 'org_edit'])->name('organizationreceivers.newinvite');
        Route::post('{name}/receivers/invite/{id}', [ReceiverController::class, 'org_invite_auth'])->name('organizationreceivers.invite');
      // confirm
        Route::get('{name}/receivers/confirm/{id}', [ReceiverController::class, 'org_confirm'])->name('organizationreceivers.confirminvite');
        Route::post('{name}/receivers/confirm/{id}', [ReceiverController::class, 'org_invite_auth'])->name('organizationreceivers.invite');
    # tippers
      // invite
        Route::get('{name}/tippers/invite/{id}', [TipperController::class, 'edit'])->name('organizationtippers.newinvite');
        Route::post('{name}/tippers/invite/{id}', [TipperController::class, 'invite_auth'])->name('organizationtippers.invite');
      // confirm
        Route::get('{name}/tippers/confirm/{id}', [TipperController::class, 'confirm'])->name('organizationtippers.confirminvite');
        Route::post('{name}/tippers/confirm/{id}', [TipperController::class, 'invite_auth'])->name('organizationtippers.invite');
    });

# organization
    Route::middleware('isOrganization')->prefix('organization')->group(function() {
        Route::post('{name}/logout', [AuthOrganizationController::class, 'destroy']);

        # dashboard
            Route::get('{name}/dashboard', function($name) {
                $org = Organization::where('slug', '=', $name)->first();
                if ( empty($org) )
                    return abort(404);
                return view('organization.dashboard', compact('org'));
            })->name('organization.dashboard');

        # groups
          // index
            Route::get('{name}/groups', function($name) {
                $org = Organization::where('slug', '=', $name)->first();
                if ( empty($org) )
                    return abort(404);
                $groups = organizationgroups($org->id);
                return view('organization.groups.index', compact('org', 'groups'));
            })->name('organizationgroups.index');

          // show
            Route::get('{name}/groups/show/{id}', [OrganizationController::class, 'orggroup_show'])->name('organizationgroups.show');

          // create
            Route::get('{name}/groups/create', function ($name) {
                $org = Organization::where('slug', '=', $name)->first();
                if ( empty($org) )
                    return abort(404);

                $users = Receiver::query()
                    ->where('org_id', '=', "$org->id")
                    ->where(['if_in_group' => null])
                    ->get();

                $tippers = Tipper::query()
                    ->where('org_id', '=', "$org->id")
                    ->where(['if_in_group' => null])
                    ->get();

                return view('organization.groups.form', compact('org', 'users', 'tippers'));
            })->name('organizationgroups.create');
            Route::post('{name}/groups/create', [OrganizationController::class, 'create_group'])->name('organizationgroups.store');

          // update
            Route::get('{name}/groups/edit/{id}', function ($name, $id) {
                $org = Organization::where('slug', '=', $name)->first();
                $org_id = $org->id;
                $group = organizationgroups::where('id', '=', $id)->first();
                $group_id = $group->id;

                $users = Receiver::select("*")
                        ->where('org_id', $org_id)
                        ->where(function($query) use ($group_id){
                            $query->where('if_in_group', '=', $group_id)
                                  ->orWhere('if_in_group', '=', null);
                        })
                        ->where('status', 200)
                        ->get();

                $tippers = Tipper::query()
                    ->where('org_id', '=', "$org->id")
                    ->where('status', 200)
                    ->get();

                if ( empty($org) || empty($group) || $group->org_id != $org->id )
                    return abort(404);

                return view('organization.groups.form', compact('org', 'group', 'users', 'tippers'));
            })->name('organizationgroups.edit');
            Route::put('{name}/groups/update/{id}', [OrganizationController::class, 'update_group'])->name('organizationgroups.update');

          // destroy
            Route::post('{name}/groups/destroy/{id}', [OrganizationController::class, 'destroy_group'])->name('organizationgroups.destroy');

        # receivers
          // index
          Route::get('{name}/receivers', [ReceiverController::class, 'org_index'])->name('organizationreceivers.index');
          // view
            Route::get('{name}/receivers/show/{id}', [ReceiverController::class, 'org_show'])->name('organizationreceivers.show');
          // block
            Route::get('{name}/receivers/block/{id}', [ReceiverController::class, 'org_block'])->name('organizationreceivers.block');
          // unblock
            Route::get('{name}/receivers/unblock/{id}', [ReceiverController::class, 'org_unblock'])->name('organizationreceivers.unblock');
          // create
            Route::get('{name}/receivers/create', [ReceiverController::class, 'org_invite_form'])->name('organizationreceivers.create');
            Route::post('{name}/receivers/create', [ReceiverController::class, 'org_store'])->name('organizationreceivers.store');
          // admin update
            Route::get('{name}/receivers/edit_info/{id}', [ReceiverController::class, 'receiver_edit'])->name('organizationreceiversinfo.edit');
            Route::post('{name}/receivers/edit_info/{id}', [ReceiverController::class, 'receiver_update'])->name('organizationreceiversinfo.update');
          // update
            Route::get('{name}/receivers/edit/{id}', [ReceiverController::class, 'org_edit'])->name('organizationreceivers.edit');
            Route::post('{name}/receivers/edit/{id}', [ReceiverController::class, 'org_update'])->name('organizationreceivers.update');
          // remove from organization
            Route::post('{name}/receivers/remove/{id}', [ReceiverController::class, 'org_remove'])->name('organizationreceivers.remove');
          // destroy
            Route::post('{name}/receivers/destroy/{id}', [ReceiverController::class, 'org_destroy'])->name('organizationreceivers.destroy');

        # dashboard
            // Route::get('{name}/group/{id}/schedule', function($name) {
            //     $org = Organization::where('slug', '=', $name)->first();
            //     if ( empty($org) )
            //         return abort(404);

            //     return view('organization.dashboard', compact('org'));
            // })->name('organization.schedule.form');

            Route::get('{name}/group/{id}/schedule', [ReceiverController::class, 'group_schedule'])->name('organization.schedule.form');

        # tippers
          // index
            Route::get('{name}/tippers', [TipperController::class, 'index'])->name('organizationtippers.index');
          // view
            Route::get('{name}/tippers/show/{id}', [TipperController::class, 'show'])->name('organizationtippers.show');
          // create
            Route::get('{name}/tippers/create', [TipperController::class, 'invite_form'])->name('organizationtippers.create');
            Route::post('{name}/tippers/create', [TipperController::class, 'store'])->name('organizationtippers.store');
          // update
            Route::get('{name}/tippers/edit/{id}', [TipperController::class, 'edit'])->name('organizationtippers.edit');
            Route::post('{name}/tippers/edit/{id}', [TipperController::class, 'update'])->name('organizationtippers.update');
          // remove from organization
            Route::post('{name}/tippers/remove/{id}', [TipperController::class, 'remove'])->name('organizationtippers.remove');
          // destroy
            Route::post('{name}/tippers/destroy/{id}', [TipperController::class, 'destroy'])->name('organizationtippers.destroy');
        # transactions
          // index
            Route::get('{name}/transactions/{model?}/{id?}', [TransactionsController::class, 'index'])->name('organizationtransactions.index');
          // destroy
            Route::post('{name}/transactions/destroy/{id}', [TransactionsController::class, 'destroy'])->name('organizationtransactions.destroy');
        # schedule
          // index
            Route::get('{name}/schedule', [ScheduleController::class, 'schedule_index'])->name('organizationschedule.index');

        # NFC Access
          // index
            Route::get('{name}/nfc-access', [NFCAccessController::class, 'index'])->name('organizationsnfcaccess.index');
    });


Route::prefix('organization')->group(function () {
    Route::get('{name}', function($name) {
        $org = Organization::where('slug', '=', $name)->first();

        if ( empty($org) )
            return abort(404);

        $answer = 'Title of organization: ' . $org->name . '<br>';
        $answer .= 'Email of organization: ' . $org->email . '<br>';
        $answer .= 'Slug: ' . $org->slug;
        $answer .= '<br><a href="'.URL::to('/organization/'.$org->slug.'/login').'"><button type="submit">login</button></a>';

        return $answer;
    });
});

Route::middleware('guest')->prefix('manager')->group(function() {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

});

# manager
Route::middleware(['isManager'])->prefix('manager')->group(function () {

    Route::get('/all-users', function(){
        $organizations = Organization::get();
        $users = User::get();
        $tippers = Tipper::get();
        $receivers = Receiver::get();
        return view('allUsers', compact('users', 'organizations', 'tippers', 'receivers'));
    });

    Route::get('all-users/organization/{id}', function($id){
        $org = Organization::where('id', '=', $id)->first();
        $groups = organizationgroups::where('org_id', '=', $id)->get();
        $receivers = Receiver::where('org_id', '=', $id)->get();
        $tippers = Tipper::where('org_id', '=', $id)->get();
        return view('allUsers-organization', compact('org', 'groups', 'receivers', 'tippers'));
    })->name('organizationAllUsers');

    Route::get('all-users/receiver/edit/{id}', function($id){
        $organizations = Organization::get();
        $receiver = Receiver::where('id', '=', $id)->first();
        return view('allUsers-receiver', compact('organizations', 'receiver'));
    });

    Route::post('all-users/receiver/save', function(Request $request){
        Receiver::where(['id' => $request->id])->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'org_id'     => $request->org_id,
        ]);
        return redirect(URL::to('manager/all-users'));
    })->name('receiverSave');
    Route::post('all-users/receiver/delete/{id}', function($id){
        Receiver::destroy($id);
        return redirect(URL::to('manager/all-users'));
    })->name('receiverDelete');

    Route::get('all-users/tipper/edit/{id}', function($id){
        $organizations = Organization::get();
        $tipper = Tipper::where('id', '=', $id)->first();
        return view('allUsers-tipper', compact('organizations', 'tipper'));
    });

    Route::post('all-users/tipper/save', function(Request $request){
        Tipper::where(['id' => $request->id])->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'org_id'     => $request->org_id,
        ]);
        return redirect(URL::to('manager/all-users'));
    })->name('tipperSave');
    Route::post('all-users/tipper/delete/{id}', function($id){
        Tipper::destroy($id);
        return redirect(URL::to('manager/all-users'));
    })->name('tipperDelete');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::view('/dashboard', 'admin.dashboard')
         ->middleware('can:full-granted');

    Route::view('/api-mobile-docs', 'admin.api_mobile_docs')
        ->middleware('can:api-mobile-granted');

    Route::view('{remaining_path}', 'admin.dashboard')
        ->where(['remaining_path' => '[\w/-]+'])
        ->middleware('can:full-granted');

    Route::redirect('/', '/manager/dashboard');

});

// relink to react
Route::resource('motivation-words', MotivationWordsController::class);
