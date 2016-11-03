<?php

use TimeTracking\View\Helper\TitleViewHelper;

return [
    'meta' => [
        'titles' => [
            TitleViewHelper::KEY_DEFAULT                                    => [
                TitleViewHelper::KEY_DEFAULT => 'DefaultTitle',
            ],
            \TimeTrackingTest\Mock\Controller\PHPUnitController::class      => [
                'index'                      => 'PHPUnitTitle',
                'test'                       => 'PHPUnitTestTitle',
                TitleViewHelper::KEY_DEFAULT => 'PHPUnitDefaultTitle',
            ],
            \TimeTrackingTest\Mock\Controller\PHPUnitErrorController::class => [
                'index' => 'PHPUnitErrorIndexTitle',
            ],
        ],
    ],
];
