<?php

use Zend\Console\Console;

require_once __DIR__ . '/../vendor/autoload.php'; // no root.php here

$console = Console::getInstance();

$targets = [
    'bin'      => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'config'   => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'public'   => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'src'      => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'test'     => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'view'     => [
        'type' => 'dir',
        'ext'  => 'php',
    ],
    'root.php' => [
        'type' => 'file',
    ],
];

function iterate_recursive($folder)
{
    $files = [];

    for ($dir = new DirectoryIterator($folder); $dir->valid(); $dir->next()) {
        if ($dir->isFile()) {
            $files[] = $dir->getPath() . '/' . $dir->getFilename();
        } elseif ($dir->isDir() && !$dir->isDot()) {
            $files = array_merge($files, iterate_recursive($dir->getPath() . '/' . $dir->getFilename()));
        }
    }

    return $files;
}

$files = [];
foreach ($targets as $target => $config) {
    if ($config['type'] === 'file') {
        $files[] = $target;
    } elseif ($config['type'] === 'dir') {
        $extLength = strlen($config['ext']);
        foreach (iterate_recursive($target) as $file) {
            if (strpos($file, $config['ext']) === strlen($file) - $extLength) {
                $files[] = $file;
            }
        }
    }
}

$files = array_unique($files);
$console->writeLine('Total amount of files: ' . count($files));

$errorCount = 0;
foreach ($files as $file) {
    $result = trim(`php -l "{$file}"`);

    if (strpos($result, 'No syntax errors detected') !== 0) {
        $errorCount++;
        $console->writeLine($result, \Zend\Console\ColorInterface::RED);
    }
}

if ($errorCount > 0) {
    $console->writeLine('Total amount of errors: ' . $errorCount);
    $console->writeLine('Errors occurred!', \Zend\Console\ColorInterface::RED);
    exit(1);
}

$console->writeLine('All files passed!', \Zend\Console\ColorInterface::GREEN);
exit(0);
