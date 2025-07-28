<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrioritasTicket;

class PrioritasController extends Controller
{
    public function index()
    {
       $prioritas = PrioritasTicket::with('ticket')->get();
    return view('admin.priority', compact('prioritas'));
}

    public function destroy($id)
{
    $prioritas = PrioritasTicket::findOrFail($id);
    $prioritas->delete();

    return redirect()->back()->with('success', 'User berhasil dihapus dari prioritas.');
}


}
