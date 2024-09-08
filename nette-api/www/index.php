<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Nette\Application\Application;

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$httpRequest = $container->getByType(Nette\Http\Request::class);
$httpResponse = $container->getByType(Nette\Http\Response::class);

$application->onRequest[] = function (Application $application, Nette\Application\Request $request) use ($httpRequest, $httpResponse): void {
    $httpResponse->setHeader('Access-Control-Allow-Headers', 'Content-Type');
};

$application->run();
