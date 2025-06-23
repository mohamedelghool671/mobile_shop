<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Api\CommentRequest;


class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService) {}

    public function index(Request $request)
    {
        return $this->commentService->index($request->limit ?? 10);
    }

    public function store(CommentRequest $request)
    {
        $data =  $this->commentService->store(fluent($request->validated()));
        return $data ? ApiResponse::sendResponse("comment publish success") :
        ApiResponse::sendResponse("error while publish",422);
    }

    public function show(Comment $comment)
    {
        return ApiResponse::sendResponse("comment return success", 200, new CommentResource($comment));
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $data = $this->commentService->update(fluent($request->validated()), $comment);
        return $data ? ApiResponse::sendResponse("comment update success") :
        ApiResponse::sendResponse("UnAuthorized", 422);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return ApiResponse::sendResponse("comment delete success");
    }

    public function latestComments(Request $request)
    {
        $data = $this->commentService->latestComments($request->limit ?? 10);
        return $data ? ApiResponse::sendResponse("latest comments", 200, $data) :
        ApiResponse::sendResponse("no new comment", 422);
    }
}
