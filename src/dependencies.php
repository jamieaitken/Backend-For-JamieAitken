<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container[\App\Controllers\Integrations\GitHubController::class] = function ($c) {
    return new \App\Controllers\Integrations\GitHubController();
};

$container[\App\Controllers\Integrations\TwitterController::class] = function ($c) {
    return new \App\Controllers\Integrations\TwitterController();
};

$container[\App\Controllers\IndexController::class] = function ($c) {
    return new \App\Controllers\IndexController();
};

$container[\App\Controllers\Integrations\GiphyController::class] = function ($c) {
    return new \App\Controllers\Integrations\GiphyController();
};

$container['errorHandler'] = function ($c) {
    return new \App\Controllers\ErrorHandler();
};