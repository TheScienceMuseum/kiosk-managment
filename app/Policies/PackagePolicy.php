<?php

namespace App\Policies;

use App\User;
use App\Package;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->can('view all packages');
    }

    public function view(User $user, Package $package)
    {
        return $user->can('view all packages');
    }

    public function create(User $user)
    {
        return $user->can('create new packages');
    }

    public function update(User $user, Package $package)
    {
        //
    }

    public function delete(User $user, Package $package)
    {
        //
    }

    public function restore(User $user, Package $package)
    {
        //
    }

    public function forceDelete(User $user, Package $package)
    {
        //
    }
}
