<?php

namespace App\Controllers;

use App\Models\User;
use App\Validation\InputValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController extends BaseController
{
    /*
     * Handling user registrations
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function signUp(Request $request, Response $response): Response
    {
        $inputData = $request->getParsedBody();
        $user = new User();

        // Validate input
        if (!InputValidator::validate(User::class, $user->getRules(), $inputData, ['email'])) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        try {
            $user->fill($inputData)->save();
        } catch (\Exception $e) {
            return $response->withStatus(500)->withJson($e->getMessage());
        }

        return $response->withJson($user);
    }
}
