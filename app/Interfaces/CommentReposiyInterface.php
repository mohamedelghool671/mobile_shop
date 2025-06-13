<?php

namespace App\Interfaces;

interface CommentReposiyInterface
{
    public function paginate($limit = 10);
    public function create(array $data);
    public function update($comment, array $data);
    public function delete($comment);
    public function latest($limit = 10);
}
