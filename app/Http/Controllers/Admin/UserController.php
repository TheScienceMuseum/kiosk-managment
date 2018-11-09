<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserShowRequest;
use App\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UserIndexRequest $request)
    {
        $users = app('App\Http\Controllers\Api\UserController')->index($request);

        return view('admin.users.index', [
            'users' => $users,
            'filters' => (object) [
                'roles' => array_pluck(Role::all(), 'name'),
            ]
        ]);
    }

    public function show(UserShowRequest $request, User $user)
    {
        $user = app('App\Http\Controllers\Api\UserController')->show($request, $user);

        return view('admin.users.show', compact('user'));
    }
}
