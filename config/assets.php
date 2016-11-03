<?php

return [
    'assets' => [
        'js'   => [
            'output_dir' => _ROOT_ . '/public/assets/js',
            'files'      => [
                'libraries.min.js' => [
                    __DIR__ . '/../assets/scripts/lib/jquery-3.1.1.js',
                ],
                'scripts.min.js'   => glob(_ROOT_ . '/assets/scripts/user/*.js'),
            ],
        ],
        'scss' => [
            'images' => [
                'prefix' => [
                    'before' => './../../img/',
                    'after'  => '/public/img/',
                ],
                'suffix' => [
                    'after' => date('YmdHi'),
                ],
            ],
            'files'  => [[
                'source'   => __DIR__ . '/../assets/scss/style.scss',
                'target'   => __DIR__ . '/../public/assets/css/style.css',
                'compiler' => 'expanded',
            ], [
                'source'   => __DIR__ . '/../public/assets/css/style.css',
                'target'   => __DIR__ . '/../public/assets/css/style.min.css',
                'compiler' => 'compressed',
            ], [
                'source'   => __DIR__ . '/../assets/scss/debug.scss',
                'target'   => __DIR__ . '/../public/assets/css/debug.css',
                'compiler' => 'expanded',
            ]],
        ],
    ],
];
