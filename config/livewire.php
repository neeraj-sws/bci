<?php

return [

    'asset_url' => null,

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_UPLOAD_DISK', 'public'),

        'rules' => null,

        'view' => 'livewire.temporary-file-upload',

        'max_upload_time' => 5, // minutes

        'preview_mimes' => [
            'png',
            'jpg',
            'jpeg',
            'gif',
            'bmp',
            'webp',
            'svg',
            'avif', // add AVIF here
        ],
    ],

];
