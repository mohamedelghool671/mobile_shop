<?php

namespace App\Helpers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
class FirebaseSendNotification {
    static function send($token,$title,$body,$imageUrl = null) {
    if (!$token) {
        return response()->json(['error' => 'token parameter is required'], 400);
    }
    $credentialsPath = storage_path('app/firebase_config.json');
    $credential = (json_decode(file_get_contents($credentialsPath), true));
    $projectId = $credential['project_id'];
    $credentials = new ServiceAccountCredentials(
        'https://www.googleapis.com/auth/firebase.messaging',
        $credential
    );
    $authToken = $credentials->fetchAuthToken();
    $accessToken = $authToken['access_token'];
    $message = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $imageUrl,
            ],
        ],
    ];

    $response = Http::withToken($accessToken)
        ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);
        $statusCode = $response->status();
         return $statusCode == 200 ? true : false;
    }
}