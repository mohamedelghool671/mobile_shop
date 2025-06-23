<?php

namespace App\Reposities;

use App\Interfaces\CommentReposiyInterface;
use App\Models\Comment;

class CommentReposity implements CommentReposiyInterface
{
    public function paginate($limit = 10)
    {
        return Comment::with(['product','user'])->paginate($limit);
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function update($comment, array $data)
    {
        return tap($comment,function($comment) use ($data) {
            return $comment->update($data);
        });
    }

    public function latest($limit = 10)
    {
        return Comment::with(['product','user'])->latest('id')->paginate($limit);
    }
}
