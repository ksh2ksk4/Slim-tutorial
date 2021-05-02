<?php
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\ContentLengthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->add(new ContentLengthMiddleware());
$app->addErrorMiddleware(true, true, true);

$container->set('myLogger', function () {
    $logger = new \Monolog\Logger('myLogger');
    $logger->pushHandler(
        new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/app.log')
    );

    return $logger;
});

$app->get('/', function (Request $request, Response $response, $args) {
    if (!$this->has('myLogger')) {
        throw new RuntimeException('No myLogger');
    }

    $message = 'Hello World';

    $logger = $this->get('myLogger');
    $logger->info($message);

    $response->getBody()->write($message);

    return $response;
});

$app->run();
