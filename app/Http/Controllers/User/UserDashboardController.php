<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Using raw query instead of relationship
        $subjects = DB::table('ticketings')
            ->where('id_user', $user->id_user)
            ->orderByDesc('created_at')
            ->get();

        return view('user.dashboard', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        DB::table('ticketings')->insert([
            'id_user' => auth()->user()->id_user, 
            'subject' => $request->subject,
            'description' => $request->deskripsi,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Data berhasil disimpan!');
    }
}