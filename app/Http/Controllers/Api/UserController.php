<?php

namespace App\Http\Controllers\Api;


use App\Filters\UserRolesFilter;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserOnBoardRequest;
use App\Http\Requests\UserRestoreRequest;
use App\Http\Requests\UserRoleIndexRequest;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserRoleResource;
use App\Http\Resources\UserResource;
use App\Mail\UserOnBoardingInviteMailable;
use App\OnBoarding\OnBoardingService;
use App\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 * @resource User
 */
class UserController extends Controller
{
    /**
     * @queryParams
     * @param UserIndexRequest $request
     * @return ResourceCollection
     */
    public function index(UserIndexRequest $request) : ResourceCollection
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'name',
                'email',
                Filter::custom('roles', UserRolesFilter::class),
            ])
            ->jsonPaginate()
        ;

        return UserResource::collection($users);
    }

    /**
     * @param UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request) : UserResource
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make(str_random(64)),
        ]);

        if ($request->input('roles')) {
            $roles = array_map(function ($role) {
                return Role::whereName($role)->first();
            }, $request->input('roles'));

            $user->syncRoles($roles);
        }

        OnBoardingService::startOnBoarding($user);

        return new UserResource($user);
    }

    /**
     * @param UserOnBoardRequest $request
     * @param User $user
     * @return UserResource
     */
    public function onboard(UserOnBoardRequest $request, User $user) : UserResource
    {
        OnBoardingService::startOnBoarding($user);

        return new UserResource($user);
    }

    /**
     * @param UserShowRequest $request
     * @param User $user
     * @return UserResource
     */
    public function show(UserShowRequest $request, User $user) : UserResource
    {
        return new UserResource($user);
    }

    /**
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, User $user) : UserResource
    {
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->input('roles')) {
            $roles = array_map(function ($role) {
                return Role::whereName($role)->first();
            }, $request->input('roles'));

            $user->syncRoles($roles);
        }

        $user->save();

        return new UserResource($user);
    }

    /**
     * @param UserDestroyRequest $request
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(UserDestroyRequest $request, User $user)
    {
        $user->delete();

        return response(null, 204);
    }

    /**
     * @param UserRestoreRequest $request
     * @param User $user
     * @return UserResource
     */
    public function restore(UserRestoreRequest $request, User $user) : UserResource
    {
        $user->restore();

        return new UserResource($user);
    }

    /**
     * @param UserRoleIndexRequest $request
     * @return ResourceCollection
     */
    public function roleIndex(UserRoleIndexRequest $request) : ResourceCollection
    {
        return UserRoleResource::collection(Role::jsonPaginate());
    }
}
