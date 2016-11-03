<?php

require_once __DIR__ . '/../root.php';

if (!check_cli_server()) {
    return false;
}

$output = new \TimeTracking\Output\Html();
$dispatcher = new \TimeTracking\Dispatcher(array_merge($_GET, $_POST));

try {
    $result = $dispatcher->dispatch();
    echo $output->formatOutput($dispatcher, $result);
} catch (Exception $error) {
    echo $output->formatError($dispatcher, $error);
}


