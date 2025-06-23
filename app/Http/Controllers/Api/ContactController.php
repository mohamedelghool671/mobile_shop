<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ContactService;
use Illuminate\Routing\Controller;
use App\Http\Requests\Api\ContactRequest;
use App\Http\Requests\Api\Contact_ResponseRequest;



class ContactController extends Controller
{

    public function __construct(protected ContactService $service)
    {
        $this->middleware('admin')->except('contact');
    }

    public function contact(ContactRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        return $this->service->createContact(fluent($data)) ? ApiResponse::sendResponse("message send success", 200) :
         ApiResponse::sendResponse("message send failed", 422);
    }

    public function all(Request $request)
    {
        $data = $this->service->getAllContacts($request->limit);
        return $data ? ApiResponse::sendResponse("list of messages", 200, $data) :
        ApiResponse::sendResponse("No Contacts", 404) ;
    }

    public function response(Contact_ResponseRequest $request)
    {
         $data = $this->service->respondToMessage($request->validated()['message_id'], $request->validated());
        return $data ? ApiResponse::sendResponse("message send success") :
        ApiResponse::sendResponse("message not found", 422);
    }
}
