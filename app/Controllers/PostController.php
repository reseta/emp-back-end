<?php

namespace App\Controllers;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Services\Image;
use App\Validation\InputValidator;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostController extends BaseController
{
    /**
     * Get blog posts
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function get(Request $request, Response $response, $args): Response
    {
        $blogPosts = Post::query();

        if (isset($args['id'])) {
            $blogPosts->where('id', $args['id']);
        }

        return $response->withJson($blogPosts->with('user')->get());
    }

    /**
     * Get own post(s)
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function getOwn(Request $request, Response $response, $args): Response
    {
        $token = $request->getAttribute('token');
        $blogPosts = Post::query();

        if (isset($args['id'])) {
            $blogPosts->where('id', $args['id']);
        } else {
            $blogPosts->where('user_id', $token['user']->id);
        }

        return $response->withJson($blogPosts->with('user')->get());
    }

    /**
     * Create blog post
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function create(Request $request, Response $response): Response
    {
        $inputData = $request->getParsedBody();

        // upload image
        if (isset($_FILES['image'])) {
            $image = new Image($_FILES['image']);

            if ($image->validate()) {
                return $response->withStatus(409)->withJson($image->getErrors());
            }

            try {
                $image->save();
            } catch (Exception $e) {
                return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
            }
        } else {
            return $response->withStatus(409)->withJson(['image' => 'File not uploaded']);
        }

        // Validate input
        if (!InputValidator::validate(Post::class, Post::getRules(), $inputData)) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        try {
            $inputData['image'] = $image->getImageRelativePath() . '/' . $image->getFileName();
            $blogPost = new Post($inputData);
            $blogPost->user_id = $request->getAttribute('token')['user']->id;
            $blogPost->save();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($blogPost);
    }

    /**
     * Update blog post
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function update(Request $request, Response $response, $args): Response
    {
        $inputData = $request->getParsedBody();

        // Validate input
        if (!InputValidator::validate(Post::class, Post::getRules('update'), $inputData)) {
            return $response->withStatus(409)->withJson(InputValidator::$errors);
        }

        $blogPost = Post::query()->find($args['postId']);

        // Check if user can edit
        if (!$this->checkEditPermissions($request->getAttribute('token'), $blogPost->user_id)) {
            return $response->withStatus(403)->withJson(['error' => 'Forbidden']);
        }

        // Update blog post
        try {
            $blogPost->fill($inputData)->save();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($blogPost);
    }

    /**
     * Delete blog post
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     *
     * @return Response
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        $token = $request->getAttribute('token');
        $blogPost = Post::query()->find($args['postId']);

        // Check if blog post exist
        if (!$blogPost) {
            return $response->withStatus(404)->withJson(['error' => 'No found']);
        }

        // Check if blog post is a user post or user is admin
        if (
            !$this->checkEditPermissions($token, $blogPost->user_id) &&
            $token['user']->role !== RolesEnum::ADMIN->value
        ) {
            return $response->withStatus(403)->withJson(['error' => 'Forbidden']);
        }

        try {
            $blogPost->delete();
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(['error' => $e->getMessage()]);
        }

        return $response->withJson($blogPost);
    }
}
