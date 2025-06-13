<?php

namespace App\Services;

use App\Helpers\Paginate;
use App\Helpers\ApiResponse;
use App\Http\Resources\CommentResource;
use App\Interfaces\CommentReposiyInterface;

class CommentService
{
    public function __construct(protected CommentReposiyInterface $commentRepo) {}

    public function index($limit = 10)
    {
        $comments = $this->commentRepo->paginate($limit);
        $data = Paginate::paginate($comments, CommentResource::collection($comments), "comments");
        return $data ? ApiResponse::sendResponse("list of comments", 200, $data) : ApiResponse::sendResponse("no comments", 422);
    }

    public function store($data, $userId)
    {
        $data['user_id'] = $userId;
        $this->commentRepo->create($data);
        return ApiResponse::sendResponse("comment publish success");
    }

    public function show($comment)
    {
        return ApiResponse::sendResponse("comment return success", 200, new CommentResource($comment));
    }

    public function update($data, $comment, $userId)
    {
        if ($comment->user_id != $userId) {
            return ApiResponse::sendResponse("UnAuthorized", 422);
        }
        $this->commentRepo->update($comment, $data);
        return ApiResponse::sendResponse("comment update success");
    }

    public function destroy($comment)
    {
        $this->commentRepo->delete($comment);
        return ApiResponse::sendResponse("comment delete success");
    }

    public function latestComments($limit = 10)
    {
        $latest = $this->commentRepo->latest($limit);
        $data = Paginate::paginate($latest, CommentResource::collection($latest), "comments");
        return $data ? ApiResponse::sendResponse("latest comments", 200, $data) : ApiResponse::sendResponse("no new comment", 422);
    }
}
