<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InputData; 
use Illuminate\Support\Facades\Auth;

class InputDataController extends Controller
{
    /**
     * Tampilkan halaman form input data.
     */
    public function create()
    {
        return view('user.index'); // atau sesuaikan jika kamu ubah nama blade-nya
    }

    /**
     * Simpan data yang diinput user.
     */
    public function store(Request $request)
    {
        // Validasi data
         $validated = $request->validate([
        'subject' => 'required|string|max:255',
        'description' => 'required|string',
    ]);
     $user = Auth::user();

     
        // Simpan ke database
        InputData::create([     
        'user_id' => $user->id_user,
        'name' => $user->name,
        'npk' => $user->npk,   
        'department' => $user->department,
        'subject' => $validated['subject'],     
        'description' => $validated['description'],
    ]);

        // Redirect dengan pesan sukses
        return redirect()->route('inputdata.create')->with('success', 'Data berhasil dikirim!');
    }
}
