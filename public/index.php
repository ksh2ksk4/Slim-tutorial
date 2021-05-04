<?php
use DI\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquenttt\Model;
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
$container->set('myDb', function () {
    $db = new Manager;
    $db->addConnection([
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'slim_tutorial',
        'username' => 'phpapp',
        'password' => '8G4mr*Z-7ap.Rm@Uz-e@',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ]);
    $db->setAsGlobal();
    $db->bootEloquent();

    return $db;
});

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
