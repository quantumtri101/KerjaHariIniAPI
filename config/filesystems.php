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
  | Filesystem Disks
  |--------------------------------------------------------------------------
  |
  | Here you may configure as many filesystem "disks" as you wish, and you
  | may even configure multiple disks of the same driver. Defaults have
  | been setup for each driver as an example of the required options.
  |
  | Supported Drivers: "local", "ftp", "sftp", "s3"
  |
  */

  'disks' => [

    'local' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file',
    ],

    'public' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/public',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'banner' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/banner',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'chat_room' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/chat_room',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'user' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/user',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'company' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/company',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'event' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/event',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'resume_id' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/resume/id',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'resume_selfie' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/resume/selfie',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],
    
    'bank' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/bank',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'jobs' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/jobs',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'jobs_document' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/jobs/document',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'user_vaccine_covid' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/user/vaccine_covid',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'user_cv' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/user/cv',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'category' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/category',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'sub_category' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/sub_category',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'pkhl' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/pkhl',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'pkwt' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/pkwt',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'check_log_document' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/check_log',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'salary_document' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/salary',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'additional_salary_document' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/additional_salary',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'chat' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/chat',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    'request_withdraw' => [
      'driver' => 'local',
      'root' => env('URL_STORAGE_PATH').'/upload_file/request_withdraw',
      'url' => env('APP_URL').'/storage',
      'visibility' => 'public',
    ],

    's3' => [
      'driver' => 's3',
      'key' => env('AWS_ACCESS_KEY_ID'),
      'secret' => env('AWS_SECRET_ACCESS_KEY'),
      'region' => env('AWS_DEFAULT_REGION'),
      'bucket' => env('AWS_BUCKET'),
      'url' => env('AWS_URL'),
      'endpoint' => env('AWS_ENDPOINT'),
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
    public_path('storage') => env('URL_STORAGE_PATH').'/upload_file/public',
  ],

];
