<?php

namespace App\Policies;

use App\User;
use App\Package;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class PackagePolicy
 * @package App\Policies
 */
class PackagePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->can('view all packages');
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function view(User $user, Package $package)
    {
        return $user->can('view all packages');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('create new packages');
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function update(User $user, Package $package)
    {
        return $user->can('edit all packages');
    }

    /**
     * @param User $user
     * @param Package $package
     * @return bool
     */
    public function delete(User $user, Package $package)
    {
        return $user->can('delete all packages');
    }
}
