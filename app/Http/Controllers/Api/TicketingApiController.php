<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticketing;

class TicketingApiController extends Controller
{
    public function index()
    {
        return Ticketing::all(); // Return semua data dalam bentuk JSON
    }

    public function show($id)
    {
        return Ticketing::findOrFail($id);
    }

    public function store(Request $request)
    {
        $ticket = Ticketing::create($request->all());
        return response()->json($ticket, 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticketing::findOrFail($id);
        $ticket->update($request->all());
        return response()->json($ticket);
    }

    public function destroy($id)
    {
        Ticketing::destroy($id);
        return response()->json(null, 204);
    }
}
