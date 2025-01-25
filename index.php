<?php
require __DIR__ . '/vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Claude\UrlShortener\Database\UrlStorage;

$app = AppFactory::create();

// Добавляем Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$storage = new UrlStorage();

$app->post('/create', function (Request $request, Response $response) use ($storage) {
    $data = json_decode($request->getBody()->getContents(), true);
    $url = $data['url'] ?? null;

    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        $response->getBody()->write(json_encode(['error' => 'Invalid URL']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $code = $storage->createShortUrl($url);
    if (!$code) {
        $response->getBody()->write(json_encode(['error' => 'Failed to create short URL']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    $shortUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . $code;
    $response->getBody()->write(json_encode(['short_url' => $shortUrl]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/{code}', function (Request $request, Response $response, array $args) use ($storage) {
    $url = $storage->findByCode($args['code']);

    if (!$url) {
        return $response->withStatus(404);
    }

    return $response->withHeader('Location', $url['url'])->withStatus(302);
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('URL Shortener Service');
    return $response;
});

$app->run();
