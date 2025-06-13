<?php

namespace App\Reposities;

use App\Interfaces\CommentReposiyInterface;
use App\Models\Comment;

class CommentReposity implements CommentReposiyInterface
{
    public function paginate($limit = 10)
    {
        return Comment::paginate($limit);
    }

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function update($comment, array $data)
    {
        return $comment->update($data);
    }

    public function delete($comment)
    {
        return $comment->delete();
    }

    public function latest($limit = 10)
    {
        return Comment::latest('id')->paginate($limit);
    }
}
