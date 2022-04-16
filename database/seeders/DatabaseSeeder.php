<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Organization,
    Permission,
    Receiver,
    ReceiverData,
    User
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->precisionPreliminarySeeding();

        $number = rand(30, 50);

        Receiver::factory($number)
            ->has(ReceiverData::factory()->count(1))
            ->hasCreditCard(1, function(array $attributes, Receiver $receiver) {
                $attributes['cardable_id'] = $receiver->id;
                $attributes['cardable_type'] = Receiver::class;
                return $attributes;
            })
            ->hasBankAccount(1, function(array $attributes, Receiver $receiver) {
                $attributes['accountable_id'] = $receiver->id;
                $attributes['accountable_type'] = Receiver::class;
                return $attributes;
            })
            ->create();

        User::factory($number)->create();

        Organization::factory($number)->create();
    }

    private function precisionPreliminarySeeding()
    {
        $admin = User::where(['email' => 'gratus@example.com'])->first();
        $apiUser = User::where(['email' => 'api_gratus@example.com'])->first();

        if (!$admin) {
            $admin = User::factory()->create([
                'email' => 'gratus@example.com',
            ]);
        }

        if (!$apiUser) {
            $apiUser = User::factory()->create([
                'email' => 'api_gratus@example.com',
            ]);
        }

        $permissions = Permission::all();

        $adminPermissions = $admin->permissions;
        $apiUserPermissions = $apiUser->permissions;

        if (!$this->shouldBeSeeded($adminPermissions, 'full-granted')) {
            $admin->permissions()->save($permissions->filter(function($value, $key) {
                return $value->name === 'full-granted';
            })->first());
        }

        if (!$this->shouldBeSeeded($apiUserPermissions, 'api-mobile-granted')) {
            $apiUser->permissions()->save($permissions->filter(function($value, $key) {
                return $value->name === 'api-mobile-granted';
            })->first());
        }
    }

    private function shouldBeSeeded($permissions = null, $permissionName = null)
    {
        $isGranted = false;

        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                if ($permission->name === $permissionName) {
                    $isGranted = true;
                    break;
                }
            }
            unset($permission);
        }

        return $isGranted;
    }
}
