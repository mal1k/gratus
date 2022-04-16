<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommonPolicy
{
    use HandlesAuthorization;

    const FULL_GRANTED = 'full-granted';

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function fullGranted(User $user) : bool
    {
        $permissions = $user->permissions;

        return $this->canDoEverything($permissions);
    }

    public function apiMobileGranted(User $user) : bool
    {
        $permissions = $user->permissions;

        return $this->canObserveApiMobileDocs($permissions);
    }

    private function canDoEverything($collection = null) : bool
    {
        return $this->isGranted($collection, self::FULL_GRANTED);
    }

    private function canObserveApiMobileDocs($collection = null) : bool
    {
        return $this->isGranted($collection, 'api-mobile-granted');
    }

    private function isGranted($collection = null, $name = null) : bool
    {
        $isGranted = false;

        if (!empty($collection)) {
            foreach ($collection as $item) {
                if ($item->name === $name || $item->name === self::FULL_GRANTED) {
                    $isGranted = true;
                    break;
                }
            }
            unset($item);
        }

        return $isGranted;
    }
}
