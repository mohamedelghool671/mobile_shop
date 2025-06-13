<?php

namespace App\Services;

use App\Helpers\Paginate;
use App\Helpers\ApiResponse;
use App\Http\Resources\ContactResource;
use App\Notifications\ContactNotification;
use App\Interfaces\ContactReposiyInterface;
use Illuminate\Support\Facades\Notification;

class ContactService
{
    public function __construct(protected ContactReposiyInterface $repo) {}

    public function createContact(array $data)
    {
        $contact =  $this->repo->create($data);
        if ($contact) {
            return ApiResponse::sendResponse("message send success", 200);
        }
        return ApiResponse::sendResponse("message send failed", 422);
    }

    public function getAllContacts($limit)
    {
        $contact = $this->repo->paginate($limit);
        if ($contact) {
            $data = Paginate::paginate($contact, ContactResource::collection($contact), "contacts");
            return ApiResponse::sendResponse("list of messages", 200, $data);
        }
           return ApiResponse::sendResponse("No Contacts", 404);
}

    public function respondToMessage($replayier_email, $messageId, $responseText)
    {
        $message = $this->repo->find($messageId);
        $userEmail = $message->user;
        if (!$message) {
            return ApiResponse::sendResponse("message not found", 422);
        }
        Notification::route('mail', $userEmail->email)
            ->notify(new ContactNotification($replayier_email, $userEmail, $responseText));
        $this->repo->delete($messageId);
        return ApiResponse::sendResponse("message send success");
    }
}
