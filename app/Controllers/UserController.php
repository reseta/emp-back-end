<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Validation\InputValidator;
use Carbon\Carbon;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use stdClass;

class UserController extends BaseController
{
    /*
     * Handling user registrations
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function signUp(Request $request, Response $response): Response
    {
        $inputData = $request->getParsedBody();
        $user = new User();

        // Validate input
        if (!InputValidator::validate(User::class, User::getRules(), $inputData, ['email'])) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        try {
            $user->fill($inputData)->save();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($user);
    }

    /*
     * Handling user registrations
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function signIn(Request $request, Response $response): Response
    {
        $inputData = $request->getParsedBody();

        // Check if user exist
        $user = User::getUserByEmail($inputData['email']);
        if (!$user) {
            return $response->withStatus(401)->withJson(['error' => 'No such user']);
        }

        // Validate input
        if (!InputValidator::validate(User::class, User::getRules('signIn'), $inputData)) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        if (!password_verify($inputData['password'], $user->password)) {
            return $response->withStatus(401)->withJson(['error' => 'Wrong password']);
        }

        $payload = [
            'user' => $user,
            'expiryDate' => Carbon::now()->addDay()->toDate(),
            'noSense' => rand(100, 999),
        ];

        $jwt = new stdClass();
        $jwt->token = JWT::encode($payload, $_ENV['APP_SECRET']);

        return $response->withJson($jwt);
    }

    /**
     * Update user
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function update(Request $request, Response $response): Response
    {
        $inputData = $request->getParsedBody();

        // Load user & check user exist
        $attr = $request->getAttribute('token');
        $user = User::getUserByEmail($attr['user']->email);
        if (!$user) {
            return $response->withStatus(404)->withJson(['error' => 'No such user']);
        }

        // Validate input
        if (!InputValidator::validate(User::class, User::getRules('update'), $inputData)) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        // Update user
        try {
            $user->fill($inputData)->save();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($user);
    }

    /**
     * Delete user
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        // Check if is admin
        $attr = $request->getAttribute('token');
        if ($attr['user']->role !== RolesEnum::ADMIN->value) {
            return $response->withStatus(403)->withJson(['error' => 'Forbidden']);
        }

        $user = User::getUserById($args['userId']);
        if (!$user) {
            return $response->withStatus(404)->withJson(['error' => 'No such user']);
        }

        try {
            $user->delete();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($user);
    }
}
