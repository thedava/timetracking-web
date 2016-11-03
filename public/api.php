<?php

require_once __DIR__ . '/../root.php';

if (!check_cli_server()) {
    return false;
}

header('Content-Type: application/json');

$output = new \TimeTracking\Output\Json();
$dispatcher = new \TimeTracking\Dispatcher(array_merge($_GET, $_POST), [
    'controller_prefix' => 'TimeTracking\\Controller\\Api\\',
]);

try {
    $result = $dispatcher->dispatch();
    echo $output->formatOutput($dispatcher, $result);
} catch (Exception $error) {
    echo $output->formatError($dispatcher, $error);
}
