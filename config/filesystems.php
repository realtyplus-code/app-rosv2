<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'disk_property' => [
            'driver' => 'local',
            'root' => storage_path('app/storage_property'),
            'url' => env('APP_URL') . '/storage_property',
            'visibility' => 'public',
        ],

        'disk_user' => [
            'driver' => 'local',
            'root' => storage_path('app/storage_user'),
            'url' => env('APP_URL') . '/storage_user',
            'visibility' => 'public',
        ],

        'disk_incident' => [
            'driver' => 'local',
            'root' => storage_path('app/storage_incident'),
            'url' => env('APP_URL') . '/storage_incident',
            'visibility' => 'public',
        ],

        'disk_incident_action' => [
            'driver' => 'local',
            'root' => storage_path('app/storage_incident_action'),
            'url' => env('APP_URL') . '/storage_incident_action',
            'visibility' => 'public',
        ],

        'disk_insurance' => [
            'driver' => 'local',
            'root' => storage_path('app/storage_insurance'),
            'url' => env('APP_URL') . '/storage_insurance',
            'visibility' => 'public',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('storage_property') => storage_path('app/storage_property'),
        public_path('storage_user') => storage_path('app/storage_user'),
        public_path('storage_incident') => storage_path('app/storage_incident'),
        public_path('storage_incident_action') => storage_path('app/storage_incident_action'),
        public_path('storage_insurance') => storage_path('app/storage_insurance'),
    ],

];
