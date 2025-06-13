<?php

namespace App\Interfaces;

interface ContactReposiyInterface
{
    public function create(array $data);
    public function paginate($limit);
    public function find($id);
    public function delete($id);
}
