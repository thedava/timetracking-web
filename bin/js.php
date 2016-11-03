<?php

use MatthiasMullie\Minify\JS;
use TimeTracking\Config;
use Zend\Console\ColorInterface;
use Zend\Console\Console;

require_once __DIR__ . '/../root.php';

$config = Config::get()['assets']['js'];

$console = Console::getInstance();
$console->writeLine('Initialized PHP-based JS compressor', ColorInterface::YELLOW);
$console->write('Configured output files: ', ColorInterface::CYAN);
$console->writeLine(count($config['files']));
$console->writeLine();

foreach ($config['files'] as $fileName => $files) {
    $js = new JS();
    foreach ($files as $file) {
        $js->add($file);
    }

    $target = $config['output_dir'] . '/' . $fileName;
    $js->minify($target);
    $console->write('Successfully compressed ', ColorInterface::GREEN);
    $console->write(count($files) . ' file(s)', ColorInterface::CYAN);
    $console->write(' into ', ColorInterface::GREEN);
    $console->write($fileName, ColorInterface::CYAN);
    $console->writeLine(' (' . round(filesize($target) / 1024, 1) . ' kB)');
}

$console->writeLine('done', ColorInterface::GREEN);
