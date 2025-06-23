<?php

namespace App\Interfaces;

interface OrderReposiyInterface
{
    public function index($limit);
    public function store($data);
    public function showUserOrders();
    public function updateStatus($id, $status);
    public function cancel($id);
}
