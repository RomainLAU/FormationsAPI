<?php

use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

require_once '../src/controller/ParticipantController.php';
require_once '../src/controller/FormationController.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->group('/participants', function (RouteCollectorProxy $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        $controller = new ParticipantController();
        $participants = $controller->getParticipants();

        if (!$participants) {
            $payload = json_encode(['status' => 404, 'data' => $participants]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode(['status' => 200, 'data' => $participants]);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->post('', function (Request $request, Response $response, $args) {

        if ($request->getHeaderLine('content-type') !== 'application/json') {
            return $response->withStatus(415);
        }

        $controller = new ParticipantController();
        $data = $request->getParsedBody();

        $controller->createParticipant($data['lastname'], $data['firstname'], $data['society']);

        return $response
            ->withStatus(200);
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $controller = new ParticipantController();
        $participant = $controller->getParticipant($args['id']);

        if (!$participant) {
            $payload = json_encode(['status' => 404, 'data' => $participant]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode(['status' => 200, 'data' => $participant]);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $controller = new ParticipantController();

        $formation = $controller->deleteParticipant($args['id']);

        if (!$formation) {
            $payload = json_encode(['status' => 400, 'data' => ['An error occured.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } else if ($formation === 400) {
            $payload = json_encode(['status' => 400, 'data' => ['This participant doesn\'t exist, you can\'t destroy things that don\'t exist...']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $payload = json_encode(['status' => 200, 'data' => ['Request successful !']]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });
});

$app->group('/formations', function (RouteCollectorProxy $group) {

    $group->get('', function (Request $request, Response $response, $args) {
        $controller = new FormationController();

        $formations = $controller->getFormations();

        if (!$formations) {
            $payload = json_encode(['status' => 404, 'data' => $formations]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode(['status' => 200, 'data' => $formations]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->get('/{id}', function (Request $request, Response $response, $args) {
        $controller = new FormationController();

        $formation = $controller->getFormation($args['id']);

        if (!$formation) {
            $payload = json_encode(['status' => 404, 'data' => ['Formation not found.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode(['status' => 200, 'data' => $formation]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->post('', function (Request $request, Response $response, $args) {

        if ($request->getHeaderLine('content-type') !== 'application/json') {
            return $response->withStatus(415);
        }

        $controller = new FormationController();
        $data = $request->getParsedBody();

        $formation = $controller->createFormation($data['name'], $data['start_date'], $data['end_date'], $data['max_participants'], $data['price']);

        if ($formation) {
            $payload = json_encode(['status' => 200, 'data' => ['Request successful !']]);
            $response->getBody()->write($payload);

            return $response
                ->withStatus(200);
        } else {

            $payload = json_encode(['status' => 400, 'data' => ['An error occured.']]);
            $response->getBody()->write($payload);

            return $response
                ->withStatus(400);
        }
    });

    $group->get('/{id}/participants', function (Request $request, Response $response, $args) {
        $controller = new ParticipantController();

        $participants = $controller->getParticipantsByFormation($args['id']);

        if (!$participants) {
            $payload = json_encode(['status' => 404, 'data' => ['The formation wasn\'t found.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $payload = json_encode(['status' => 200, 'data' => $participants]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->post('/{formationId}/participants/{participantId}', function (Request $request, Response $response, $args) {
        $controller = new FormationController();

        $formation = $controller->addParticipantToFormation($args['formationId'], $args['participantId']);

        if (!$formation) {
            $payload = json_encode(['status' => 400, 'data' => ['An error occured.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } else if ($formation === 409) {
            $payload = json_encode(['status' => 400, 'data' => ['This person already participates to this formation or there are too many participants to this formation.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $payload = json_encode(['status' => 200, 'data' => ['Request successful !']]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{formationId}/participants/{participantId}', function (Request $request, Response $response, $args) {
        $controller = new FormationController();

        $formation = $controller->removeParticipantOfFormation($args['formationId'], $args['participantId']);

        if (!$formation) {
            $payload = json_encode(['status' => 400, 'data' => ['An error occured.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } else if ($formation === 400) {
            $payload = json_encode(['status' => 400, 'data' => ['This person doesn\'t participates to this formation, the formation doesn\'t exist or the participant doesn\'t exist.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $payload = json_encode(['status' => 200, 'data' => ['Request successful !']]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });

    $group->delete('/{id}', function (Request $request, Response $response, $args) {
        $controller = new FormationController();

        $formation = $controller->deleteFormation($args['id']);

        if (!$formation) {
            $payload = json_encode(['status' => 400, 'data' => ['An error occured.']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        } else if ($formation === 400) {
            $payload = json_encode(['status' => 400, 'data' => ['This formation doesn\'t exist, you can\'t destroy things that don\'t exist...']]);
            $response->getBody()->write($payload);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $payload = json_encode(['status' => 200, 'data' => ['Request successful !']]);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json');
    });
});


$app->run();
