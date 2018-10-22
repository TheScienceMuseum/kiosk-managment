<?php

namespace App\Policies;

use App\User;
use App\Kiosk;
use Illuminate\Auth\Access\HandlesAuthorization;

class KioskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the kiosk.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->can('view all kiosks');
    }

    /**
     * Determine whether the user can view the kiosk.
     *
     * @param  \App\User  $user
     * @param  \App\Kiosk  $kiosk
     * @return mixed
     */
    public function view(User $user, Kiosk $kiosk)
    {
        return $user->can('view all kiosks');
    }

    /**
     * Determine whether the user can update the kiosk.
     *
     * @param  \App\User  $user
     * @param  \App\Kiosk  $kiosk
     * @return mixed
     */
    public function update(User $user, Kiosk $kiosk)
    {
        return $user->can('edit all kiosks');
    }

    /**
     * Determine whether the user can deploy a package to the kiosk.
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function assignPackage(User $user, Kiosk $kiosk)
    {
        return $user->can('deploy packages to all kiosks');
    }

    /**
     * Determine whether the user can delete the kiosk.
     *
     * @param  \App\User  $user
     * @param  \App\Kiosk  $kiosk
     * @return mixed
     */
    public function delete(User $user, Kiosk $kiosk)
    {
        //
    }
}
