<?php

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Formatter\Compressed;
use Leafo\ScssPhp\Formatter\Expanded;
use TimeTracking\Config;
use Zend\Console\ColorInterface;
use Zend\Console\Console;

require_once __DIR__ . '/../root.php';

$expanded = function ($config) {
    $compiler = new Compiler();
    $compiler->setFormatter(Expanded::class);
    $compiler->setImportPaths([_ROOT_ . '/assets/scss']);
    $compiler->registerFunction('url', function ($value) use ($config) {
        $backgroundImage = $value[0][2][0];
        if (strpos($backgroundImage, $config['images']['prefix']['after']) === 0) {
            $backgroundImage = $config['images']['prefix']['before'] . substr($backgroundImage, strlen($config['images']['prefix']['after']));
        }
        return sprintf('url("%s?%d")', $backgroundImage, $config['images']['suffix']['after']);
    });

    return $compiler;
};

$compressed = function () {
    $compiler = new Compiler();
    $compiler->setFormatter(Compressed::class);

    return $compiler;
};

$config = Config::get()['assets']['scss'];
$config['compilers'] = [];
foreach ($config['files'] as $cfg) {
    $config['compilers'][] = [
        'source'   => $cfg['source'],
        'target'   => $cfg['target'],
        'callback' => ($cfg['compiler'] == 'expanded') ? $expanded : $compressed,
    ];
}

$console = Console::getInstance();
$console->writeLine('Initialized PHP-based SCSS compiler', ColorInterface::YELLOW);
$console->write('Configured compilers: ', ColorInterface::CYAN);
$console->writeLine(count($config['compilers']));
$console->writeLine();

foreach ($config['compilers'] as $compilerConfig) {
    /** @var Compiler $compiler */
    $compiler = call_user_func($compilerConfig['callback'], $config);

    $src = file_get_contents($compilerConfig['source']);
    $result = $compiler->compile($src, dirname($compilerConfig['source']));
    file_put_contents($compilerConfig['target'], $result);

    $console->write('Successfully compiled ', ColorInterface::GREEN);
    $console->write(basename($compilerConfig['source']), ColorInterface::CYAN);
    $console->write(' into ', ColorInterface::GREEN);
    $console->write(basename($compilerConfig['target']), ColorInterface::CYAN);
    $console->writeLine(' (' . round(strlen($result) / 1024, 1) . ' kB)');
}

$console->writeLine('done', ColorInterface::GREEN);
