<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\ApiIndexRequest;
use App\Kiosk;
use App\User;

class ApiInformationController extends Controller
{
    public function resources(ApiIndexRequest $request) {
        $paginationQueryParams = [
            'page' => [
                config('json-api-paginate.number_parameter') => 'integer',
                config('json-api-paginate.size_parameter') => 'integer',
            ],
        ];


        return [
            'resources' => [
                'user_role' => [
                    'index' => [
                        'method' => 'GET',
                        'path' => route('api.user.role.index'),
                        'params' => array_merge([
                            'filters' => [
                                'name' => 'string',
                            ]
                        ], $paginationQueryParams),
                    ],
                ],
                'user' => [
                    'index' => [
                        'method' => 'GET',
                        'path' => route('api.user.index'),
                        'params' => array_merge([
                            'filters' => [
                                'name' => 'string',
                                'email' => 'string',
                                'role' => 'string',
                            ]
                        ], $paginationQueryParams),
                    ],
                    'store' => [
                        'method' => 'POST',
                        'path' => route('api.user.store'),
                    ],
                    'show' => [
                        'method' => 'GET',
                        'path' => route('api.user.show', User::first()),
                    ],
                    'update' => [
                        'method' => 'PUT',
                        'path' => route('api.user.update', User::first()),
                    ],
                    'destroy' => [
                        'method' => 'DELETE',
                        'path' => route('api.user.destroy', User::first()),
                    ],
                ],
                'kiosk' => [
                    'index' => [
                        'method' => 'GET',
                        'path' => route('api.kiosk.index'),
                        'params' => array_merge([
                            'filters' => [
                                'name' => 'string',
                                'registered' => 'boolean',
                            ]
                        ], $paginationQueryParams),
                    ],
                    'show' => [
                        'method' => 'GET',
                        'path' => route('api.kiosk.show', Kiosk::first()),
                    ],
                    'update' => [
                        'method' => 'PUT',
                        'path' => route('api.kiosk.update', Kiosk::first()),
                    ],
                    'destroy' => [
                        'method' => 'DELETE',
                        'path' => route('api.kiosk.destroy', Kiosk::first()),
                    ],
                    'register' => [
                        'method' => 'POST',
                        'path' => route('api.kiosk.register'),
                    ]
                ],
            ]
        ];
    }
}
