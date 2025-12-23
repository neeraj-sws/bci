<?php
return [
    'show_warnings' => false,
    'public_path' => null,
    'convert_entities' => true,

    'options' => [
        'font_dir' => storage_path('fonts'), // can remain as storage folder
        'font_cache' => storage_path('fonts'),
'font_family' => [
    'inter' => [
        'normal' => public_path('assets/fonts/InterRegular.ttf'), // 400
        'bold'   => public_path('assets/fonts/InterMedium.ttf'),  // Use Medium as “bold”
        '700'    => public_path('assets/fonts/InterBold.ttf'),    // True Bold
    ],
],



        'default_font' => 'inter',
        'chroot' => realpath(base_path()),
        'enable_remote' => false,
    ],

];
