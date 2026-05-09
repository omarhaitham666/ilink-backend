<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Post\PostService;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Create post
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = $this->postService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    /**
     * Update post
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $updatedPost = $this->postService->update(
            $post,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $updatedPost,
        ]);
    }

    /**
     * Delete post
     */
    public function destroy(Post $post, Request $request): JsonResponse
    {
        $this->postService->delete(
            $post,
            $request->user()
        );

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}