<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Contact_ResponseRequest;
use Illuminate\Http\Request;
use App\Services\ContactService;
use Illuminate\Routing\Controller;
use App\Http\Requests\Api\ContactRequest;



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
        return $this->service->createContact($data);
    }

    public function all(Request $request)
    {
        return $this->service->getAllContacts($request->limit);

    }

    public function response(Contact_ResponseRequest $request)
    {
        return $this->service->respondToMessage($request->user()->email, $request->validated()['message_id'], $request->validated());
    }
}
