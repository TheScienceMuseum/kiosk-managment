<?php

namespace App\Policies;

use App\PackageVersion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class PackageVersionPolicy
 * @package App\Policies
 */
class PackageVersionPolicy
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
     * @param PackageVersion $packageVersion
     * @return bool
     */
    public function view(User $user, PackageVersion $packageVersion)
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
     * @param PackageVersion $packageVersion
     * @return bool
     */
    public function update(User $user, PackageVersion $packageVersion)
    {
        return $user->can('edit all packages');
    }

    /**
     * @param User $user
     * @param PackageVersion $packageVersion
     * @return bool
     */
    public function approve(User $user, PackageVersion $packageVersion)
    {
        return $user->can('publish all packages');
    }
}
