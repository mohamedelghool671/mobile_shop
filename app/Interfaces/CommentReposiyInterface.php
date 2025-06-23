<?php

namespace App\Interfaces;

interface CommentReposiyInterface
{
    public function paginate($limit);
    public function create(array $data);
    public function update($comment, array $data);
    public function latest($limit);
}
