<?php

namespace App\Reposities;

use App\Models\Contact;
use App\Interfaces\ContactReposiyInterface;

class ContactReposity implements ContactReposiyInterface
{
    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function paginate($limit)
    {
        return Contact::with('user')->paginate($limit);
    }

    public function find($id)
    {
        return Contact::find($id);
    }

    public function delete($id)
    {
        $message = $this->find($id);
        return $message ? $message->delete() : false;
    }
}
