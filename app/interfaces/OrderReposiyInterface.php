<?php

namespace App\Interfaces;

interface OrderReposiyInterface
{
    public function index($limit);
    public function store($data, $user);
    public function showUserOrders($user);
    public function updateStatus($id, $status);
    public function cancel($id);
}
