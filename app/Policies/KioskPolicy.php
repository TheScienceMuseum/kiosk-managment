<?php

namespace App\Policies;

use App\User;
use App\Kiosk;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class KioskPolicy
 * @package App\Policies
 */
class KioskPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->can('view all kiosks');
    }

    /**
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function view(User $user, Kiosk $kiosk)
    {
        return $user->can('view all kiosks');
    }

    /**
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function update(User $user, Kiosk $kiosk)
    {
        return $user->can('edit all kiosks');
    }

    /**
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function assignPackage(User $user, Kiosk $kiosk)
    {
        return $user->can('deploy packages to all kiosks');
    }

    /**
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function viewLogs(User $user, Kiosk $kiosk)
    {
        return $user->can('view kiosk logs');
    }

    /**
     * @param User $user
     * @param Kiosk $kiosk
     * @return bool
     */
    public function delete(User $user, Kiosk $kiosk)
    {
        return $user->can('destroy all kiosks');
    }
}
