<?php

require_once __DIR__ . '/../../root.php';

$autoLoader = ComposerAutoloaderInitTimeTracking::getLoader();
$autoLoader->addPsr4('TimeTrackingTest\\', _ROOT_ . '/test/TimeTrackingTest');

$locations = \TimeTracking\Config::getLocations();
$locations[] = '/test/bootstrap/config/*_test.php';
\TimeTracking\Config::setLocations($locations);
\TimeTracking\Config::get(true);
