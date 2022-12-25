<?php

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..', '.env.local');
$dotenv->load();

require_once '../src/controller/ParticipantController.php';
require_once '../src/controller/FormationController.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
    $data = 'I like potatoes';
    $response->getBody()->write("Hello world!" . $data);
    return $response;
});

// $app->get('/formations[/{lang}]', function (Request $request, Response $response, $args) {

//     $data = ['PHP', 'JS', 'ReactJS', 'VueJS'];
//     $payload = json_encode($data);
//     // $params = $args['lang'];
//     $response->getBody()->write($payload);

//     return $response
//         ->withHeader('Content-Type', 'application/json');
// });

$app->post('/participants/create', function (Request $request, Response $response, $args) {

    if ($request->getHeaderLine('content-type') !== 'application/json') {
        return $response->withStatus(415);
    }

    $controller = new ParticipantController();
    $data = $request->getParsedBody();

    $controller->createParticipant($data['lastname'], $data['firstname'], $data['society']);

    return $response
        ->withStatus(200);
});

$app->get('/participants', function (Request $request, Response $response, $args) {
    $controller = new ParticipantController();
    $participants = $controller->getParticipants();

    if (!$participants) {
        $payload = json_encode(['status' => 404, 'data' => $participants]);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404);
    }

    $payload = json_encode($participants);
    $response->getBody()->write($payload);

    return $response
        ->withHeader('Content-Type', 'application/json');
});

$app->get('/participants/{id}', function (Request $request, Response $response, $args) {
    $controller = new ParticipantController();
    $participant = $controller->getParticipant($args['id']);

    if (!$participant) {
        $payload = json_encode(['status' => 404, 'data' => $participant]);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404);
    }

    $payload = json_encode($participant);
    $response->getBody()->write($payload);

    return $response
        ->withHeader('Content-Type', 'application/json');
});

$app->post('/formations/create', function (Request $request, Response $response, $args) {

    if ($request->getHeaderLine('content-type') !== 'application/json') {
        return $response->withStatus(415);
    }

    $controller = new FormationController();
    $data = $request->getParsedBody();

    $controller->createFormation($data['name'], $data['start_date'], $data['end_date'], $data['max_participants'], $data['price']);

    return $response
        ->withStatus(200);
});


$app->run();
