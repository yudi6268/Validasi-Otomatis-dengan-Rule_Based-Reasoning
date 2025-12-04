<?php

return [

    'mode'                  => 'utf-8',
    'format'                => 'F4',
    'default_font'          => 'Arial',
    'font_dir'              => base_path('resources/fonts/'),
    'font_cache'            => storage_path('fonts/'),
    'temp_dir'              => sys_get_temp_dir(),

    'margin_left'           => 10,
    'margin_right'          => 10,
    'margin_top'            => 10,
    'margin_bottom'         => 10,

    'margin_header'         => 9,
    'margin_footer'         => 9,

    'orientation'           => 'portrait',

    'defines_orientation'   => false,

    'defines_size'          => false,

    'show_warnings'         => false,

    'show_errors'           => false,

    'show_html5_parser_errors' => false,

    'scaleToFit'            => true,

    'logOutputFile'         => null,

    'tempDir'               => sys_get_temp_dir(),

    'allowed_protocols'     => [
        'file://',
        'http://',
        'https://',
    ],

    'svg'                   => [
        'enabled'           => true,
        'embed'             => true,
        'embed_max_size'    => 32768,
    ],

    'enable_font_subsetting' => false,

    'enable_remote'         => true,

    'enable_javascript'     => true,

    'javascript_delay'      => 0,

    'enable_html5_parser'   => true,

];
