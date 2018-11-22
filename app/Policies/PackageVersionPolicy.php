<?php

namespace App\Policies;

use App\PackageVersion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackageVersionPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->can('view all packages');
    }

    public function view(User $user, PackageVersion $packageVersion)
    {
        return $user->can('view all packages');
    }

    public function create(User $user)
    {
        return $user->can('create new packages');
    }

    public function update(User $user, PackageVersion $packageVersion)
    {
        return $user->can('edit all packages');
    }

    public function delete(User $user, PackageVersion $packageVersion)
    {
        //
    }

    public function restore(User $user, PackageVersion $packageVersion)
    {
        //
    }

    public function forceDelete(User $user, PackageVersion $packageVersion)
    {
        //
    }
}
