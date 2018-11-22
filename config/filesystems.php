<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'assets' => [
            'driver' => 's3',
            'key' => env('FILESYSTEM_ASSETS_STORAGE_KEY'),
            'secret' => env('FILESYSTEM_ASSETS_STORAGE_SECRET'),
            'region' => env('FILESYSTEM_ASSETS_STORAGE_REGION'),
            'bucket' => env('FILESYSTEM_ASSETS_STORAGE_BUCKET'),
            'url' => env('FILESYSTEM_ASSETS_STORAGE_URL'),
        ],

        'builds' => [
            'driver' => 's3',
            'key' => env('FILESYSTEM_BUILD_STORAGE_KEY'),
            'secret' => env('FILESYSTEM_BUILD_STORAGE_SECRET'),
            'region' => env('FILESYSTEM_BUILD_STORAGE_REGION'),
            'bucket' => env('FILESYSTEM_BUILD_STORAGE_BUCKET'),
            'url' => env('FILESYSTEM_BUILD_STORAGE_URL'),
        ],

        'packages' => [
            'driver' => 's3',
            'key' => env('FILESYSTEM_PACKAGE_STORAGE_KEY'),
            'secret' => env('FILESYSTEM_PACKAGE_STORAGE_SECRET'),
            'region' => env('FILESYSTEM_PACKAGE_STORAGE_REGION'),
            'bucket' => env('FILESYSTEM_PACKAGE_STORAGE_BUCKET'),
            'url' => env('FILESYSTEM_PACKAGE_STORAGE_URL'),
        ],

        'build-temp' => [
            'driver' => 'local',
            'root' => storage_path('app/builds'),
        ],

    ],

];
