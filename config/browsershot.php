<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Chrome/Chromium Binary Path
  |--------------------------------------------------------------------------
  |
  | Path ke Chrome atau Chromium binary. Jika null, Browsershot akan
  | mencoba auto-detect dari Puppeteer atau system PATH.
  |
  | Common paths:
  | - Linux: /usr/bin/chromium-browser, /usr/bin/google-chrome
  | - Windows: C:\Program Files\Google\Chrome\Application\chrome.exe
  | - Mac: /Applications/Google Chrome.app/Contents/MacOS/Google Chrome
  | - Puppeteer: node_modules/puppeteer/.local-chromium/...
  |
  */
  'chrome_path' => env('BROWSERSHOT_CHROME_PATH', null),

  /*
  |--------------------------------------------------------------------------
  | Node.js Binary Path
  |--------------------------------------------------------------------------
  |
  | Path ke Node.js binary. Jika null, akan menggunakan 'node' dari PATH.
  |
  */
  'node_path' => env('BROWSERSHOT_NODE_PATH', null),

  /*
  |--------------------------------------------------------------------------
  | NPM Binary Path
  |--------------------------------------------------------------------------
  |
  | Path ke npm binary. Jika null, akan menggunakan 'npm' dari PATH.
  |
  */
  'npm_path' => env('BROWSERSHOT_NPM_PATH', null),

  /*
  |--------------------------------------------------------------------------
  | Puppeteer Node Modules Path
  |--------------------------------------------------------------------------
  |
  | Path ke node_modules yang berisi Puppeteer.
  | Default: base_path('node_modules')
  |
  */
  'node_modules_path' => env('BROWSERSHOT_NODE_MODULES_PATH', null),

  /*
  |--------------------------------------------------------------------------
  | No Sandbox Mode
  |--------------------------------------------------------------------------
  |
  | Aktifkan no-sandbox mode untuk server headless/VPS.
  | REQUIRED untuk running Chrome sebagai root atau di Docker.
  |
  */
  'no_sandbox' => env('BROWSERSHOT_NO_SANDBOX', true),

  /*
  |--------------------------------------------------------------------------
  | Disable GPU
  |--------------------------------------------------------------------------
  |
  | Disable GPU acceleration untuk headless mode.
  | Recommended untuk server tanpa GPU.
  |
  */
  'disable_gpu' => env('BROWSERSHOT_DISABLE_GPU', true),

  /*
  |--------------------------------------------------------------------------
  | Timeout
  |--------------------------------------------------------------------------
  |
  | Timeout dalam detik untuk rendering PDF.
  | Default: 60 detik
  |
  */
  'timeout' => env('BROWSERSHOT_TIMEOUT', 60),

  /*
  |--------------------------------------------------------------------------
  | Paper Sizes
  |--------------------------------------------------------------------------
  |
  | Definisi ukuran kertas custom.
  | Format: [width, height] dalam milimeter
  |
  */
  'paper_sizes' => [
    'f4' => [
      'width' => 216,
      'height' => 330,
    ],
    'folio' => [
      'width' => 216,
      'height' => 330,
    ],
    'a4' => [
      'width' => 210,
      'height' => 297,
    ],
    'letter' => [
      'width' => 216,
      'height' => 279,
    ],
    'legal' => [
      'width' => 216,
      'height' => 356,
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Default Margins
  |--------------------------------------------------------------------------
  |
  | Margin default untuk PDF dalam milimeter.
  |
  */
  'default_margins' => [
    'top' => 15,
    'right' => 12,
    'bottom' => 15,
    'left' => 12,
  ],

  /*
  |--------------------------------------------------------------------------
  | Extra Chrome Arguments
  |--------------------------------------------------------------------------
  |
  | Arguments tambahan untuk Chrome.
  | Useful untuk troubleshooting atau optimization.
  |
  */
  'chrome_args' => [
    '--disable-web-security',
    '--disable-features=VizDisplayCompositor',
    '--disable-dev-shm-usage',
    '--disable-setuid-sandbox',
    '--single-process',
  ],

  /*
  |--------------------------------------------------------------------------
  | Storage Settings
  |--------------------------------------------------------------------------
  |
  | Pengaturan untuk penyimpanan PDF.
  |
  */
  'storage' => [
    'disk' => env('BROWSERSHOT_STORAGE_DISK', 'local'),
    'path' => env('BROWSERSHOT_STORAGE_PATH', 'perjanjian-pdfs'),
  ],
];
