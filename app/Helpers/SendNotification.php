<?php

namespace App\Helpers;

use App\Models\User;
use App\Jobs\FirebaseNotification;
use App\Notifications\UserNotification;
use App\Notifications\OrderNotification;


class SendNotification {
    static function sendAll($title,$body,$image) {
         $users = User::with('deviceTokens')->get();
        foreach($users as $user) {
            $user->notify(new UserNotification($title,$body,$image));
            $token = $user->getDeviceTokens();
            if (!$token) {
                continue;
            }
            dispatch(new FirebaseNotification($token,$title,$body,$image));
        }
    }

    static function sendTo($user,$message) {
         $user->notify(new OrderNotification($message));
        dispatch(new FirebaseNotification($user->getDeviceTokens(),$message['title'],$message['body']));
    }

}