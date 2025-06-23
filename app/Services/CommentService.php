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

    public function store($data)
    {
        $userId = auth()->id();
        $data['user_id'] = $userId;
        return $this->commentRepo->create($data->toArray());
    }

    public function update($data, $comment)
    {
        $userId = auth()->id();
        if ($comment->user_id != $userId) {
            return false;
        }
        return $this->commentRepo->update($comment, $data->toArray());
    }

    public function latestComments($limit = 10)
    {
        $latest = $this->commentRepo->latest($limit);
        return Paginate::paginate($latest, CommentResource::collection($latest), "comments");
    }
}
