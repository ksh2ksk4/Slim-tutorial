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

$logger = new My\Logger();
$container->set('myLogger', $logger->logger);
$db = new My\Db();
$container->set('myDb', $db->manager);

$app->get('/', function (Request $request, Response $response, $args) {
    if (!$this->has('myLogger')) {
        throw new RuntimeException('No myLogger');
    }

    $message = 'Hello World';

    $logger = $this->get('myLogger');
    $logger->info($message);

    $response->getBody()->write($message);

    if (!$this->has('myDb')) {
        throw new RuntimeException('No myDb');
    }

    $db = $this->get('myDb');
    foreach ($db::select('show databases') as $k => $v) {
        $logger->info($v->Database);
    }

    return $response;
});

$app->run();
