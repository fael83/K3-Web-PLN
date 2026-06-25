<?php

return [

    'imports' => [
        'read_only' => true,
        'ignore_empty' => false,
        'heading_row' => [
            'formatter' => 'slug',
        ],
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
            'input_encoding' => 'UTF-8',
        ],
    ],

    'exports' => [
        'chunk_size' => 100,
        'pre_calculate_formulas' => false,
        'strict_null_comparison' => false,
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
            'include_separator_line' => false,
            'excel_compatibility' => false,
            'output_encoding' => 'UTF-8',
        ],
    ],

    'extension_detector' => [
        'xlsx' => 'Xlsx',
        'xlsm' => 'Xlsx',
        'xltx' => 'Xlsx',
        'xltm' => 'Xlsx',
        'xls' => 'Xls',
        'xlt' => 'Xls',
        'ods' => 'Ods',
        'ots' => 'Ods',
        'slk' => 'Slk',
        'xml' => 'Xml',
        'gnumeric' => 'Gnumeric',
        'htm' => 'Html',
        'html' => 'Html',
        'csv' => 'Csv',
        'tsv' => 'Csv',
        'pdf' => 'Dompdf',
    ],

    'value_binder' => [
        'default' => null,
    ],

    'cache' => [
        'driver' => 'memory',
    ],

    'temporary_files' => [
        'local_path' => storage_path('framework/cache/laravel-excel'),
        'remote_disk' => null,
        'force_resync_remote' => null,
    ],

];