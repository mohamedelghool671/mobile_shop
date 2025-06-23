<?php

namespace App\Services;

use App\Helpers\Paginate;
use App\Jobs\MessageRespondJob;
use App\Http\Resources\ContactResource;
use App\Notifications\ContactNotification;
use App\Interfaces\ContactReposiyInterface;
use Illuminate\Support\Facades\Notification;

class ContactService
{
    public function __construct(protected ContactReposiyInterface $repo) {}

    public function createContact($data)
    {
        return $this->repo->create($data->toArray());
    }

    public function getAllContacts($limit)
    {
        $contact = $this->repo->paginate($limit);
        if ($contact) {
            $data = Paginate::paginate($contact, ContactResource::collection($contact), "contacts");
            return $data;
        }
}

    public function respondToMessage($messageId, $responseText)
    {
        $message = $this->repo->find($messageId);
        if (!$message) {
            return false;
        }
             Notification::route('mail', $message->email)
            ->notify(new ContactNotification(auth()->user()->email, $message->name, $responseText));
        return $this->repo->delete($messageId);
    }
}
