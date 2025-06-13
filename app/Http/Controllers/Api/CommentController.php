<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Controllers\Controller;
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
        return $this->commentService->store($request->validated(), $request->user()->id);
    }

    public function show(Comment $comment)
    {
        return $this->commentService->show($comment);
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        return $this->commentService->update($request->validated(), $comment, $request->user()->id);
    }

    public function destroy(Comment $comment)
    {
        return $this->commentService->destroy($comment);
    }

    public function latestComments(Request $request)
    {
        return $this->commentService->latestComments($request->limit ?? 10);
    }
}
