<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (Request $request, RequestHandler $handler) {
    $storedHash = env('HASHED_PASSWORD');

    $authHeader = $request->getHeaderLine('Authorization');

    if (str_starts_with($authHeader, 'Bearer ')) {
        $submittedPassword = substr($authHeader, 7);
    } else {
        $submittedPassword = null;
    }

    if ($submittedPassword && password_verify($storedHash, $submittedPassword)) {
        return $handler->handle($request);
    }

    $response = new \Slim\Psr7\Response();
    $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
    return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
};