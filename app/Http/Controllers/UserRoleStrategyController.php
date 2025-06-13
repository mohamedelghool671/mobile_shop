<?php

namespace App\Http\Controllers;

use App\design_pattern\factory\factory_creator\UserRoleFactory;
use Illuminate\Http\Request;

class UserRoleStrategyController extends Controller
{
    public function getrole($role='user') {
        $userRole = UserRoleFactory::getStrategy($role);
       return $userRole->getPermission();
    }
}
