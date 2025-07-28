<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function index()
    {
        $url = config('services.dakar.base_url') . '/users';

        $response = Http::get($url);

        $employees = $response->json();

        return view('employees.index', compact('employees'));
    }
}
