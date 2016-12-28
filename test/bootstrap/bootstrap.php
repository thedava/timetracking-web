<?php

require_once __DIR__ . '/../../root.php';

$autoLoader = ComposerAutoloaderInitTimeTracking::getLoader();
$autoLoader->addPsr4('TimeTrackingTest\\', _ROOT_ . '/test/TimeTrackingTest');

$locations = \TheDava\Config::getLocations();
$locations[] = '/test/bootstrap/config/*_test.php';
\TheDava\Config::setLocations($locations);
\TheDava\Config::get(true);
