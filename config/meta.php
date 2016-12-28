<?php

use TheDava\View\Helper\TitleViewHelper;

return [
    'meta' => [
        'titles' => [
            TitleViewHelper::KEY_DEFAULT                    => [
                TitleViewHelper::KEY_DEFAULT => 'TimeTracking',
            ],
            \TimeTracking\Controller\IndexController::class => [
                TitleViewHelper::KEY_DEFAULT => 'TimeTracking - Home',
            ],
        ],
    ],
];
