<?php

// Routes

$app->group('/integrations', function () {
    $this->get('/github', App\Controllers\Integrations\GitHubController::class . ':getReposRoute');
    $this->get('/twitter', App\Controllers\Integrations\TwitterController::class . ':getDetailsRoute');
    $this->get('/giphy', \App\Controllers\Integrations\GiphyController::class . ':getGifRoute');
});
