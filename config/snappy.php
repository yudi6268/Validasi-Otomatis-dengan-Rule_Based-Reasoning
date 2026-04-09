<?php

// Helper untuk proper path quoting di Windows
$getWkhtmltopdfPath = function() {
    $path = env('SNAPPY_BINARY_PATH', env('WKHTMLTOPDF_BINARY', '/usr/bin/wkhtmltopdf'));
    
    // Jika Windows dan path punya spasi, wrap dengan quotes
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && strpos($path, ' ') !== false) {
        // Remove existing quotes if any
        $path = trim($path, '"\'');
        return '"' . $path . '"';
    }
    
    return $path;
};

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |    
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |    
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |    
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */
    
    'pdf' => [
        'enabled' => true,
        // Binary path dengan proper Windows support
        'binary'  => $getWkhtmltopdfPath(),
        'timeout' => 300, // Increase timeout for complex docs
        'options' => [
            // Safe options for rendering
            'enable-local-file-access' => true,
            'print-media-type' => true,
            'orientation' => 'Portrait', // Default, can be overridden per page
            'page-size' => 'Folio',      // Default F4/Folio
            'disable-smart-shrinking' => true,
        ],
        'env'     => [],
    ],
    
    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '/usr/local/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
