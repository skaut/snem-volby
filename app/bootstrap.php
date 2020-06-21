<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$tempDir = dirname(__DIR__) . '/temp';
$logDir  = __DIR__ . '/../log';

putenv('TMPDIR=' . $tempDir);

$configurator = new Nette\Configurator();
$configurator->setDebugMode(getenv('DEVELOPMENT_MACHINE') === 'true');
$configurator->enableDebugger($logDir);
$configurator->setTempDirectory($tempDir);

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register(true);

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$configurator->addParameters([
    'logDir' => $logDir,
    'isCli' => PHP_SAPI === 'cli',
]);

return $configurator->createContainer();
