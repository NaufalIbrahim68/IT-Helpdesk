<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ManajemenUserController extends Controller
{
    public function index()
    {
        // Ambil data dari API
        $response = Http::get(config('services.dakar.base_url') . '/users');

        // Ambil hasil JSON sebagai array
         $users = $response['data'];

        return view('Admin.manajemen-user', compact('users'));
    }
}
