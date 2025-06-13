<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where("email", $request->email)->first();
        if ($user) {
            if ($user->email_verified_at !== null) {
                return $next($request);
            }
            return ApiResponse::sendResponse("Please verify your email", 403);
        }
        return ApiResponse::sendResponse("User not found", 404);
    }
}