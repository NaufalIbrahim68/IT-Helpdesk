<?php

namespace App\Http\Controllers;

use App\Models\PrioritasTicket;
use Illuminate\Http\Request;
use App\Models\Ticketing;
use App\Models\Priority;

class TicketingController extends Controller
{
  public function index(Request $request)
{
    $query = Ticketing::query();

    // Filter by status (jika ada)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by search (jika ada)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('subject', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('ticketing.index', compact('tickets'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    /** @var \App\Models\User $user */
    $user = auth()->user();

    Ticketing::create([
        'id_user'     => $user->id_user, // jika memang kolomnya 'id_user'
        'subject'     => ucfirst($request->input('subject')),
        'description' => $request->input('description'),
        'status'      => Ticketing::STATUS_PENDING ?? 'pending',
        'name'        => $user->name,
        'npk'         => $user->npk,
        'department'  => $user->department,
    ]);

    return redirect()->route('inputdata.create')->with('success', 'Ticket berhasil dikirim!');
}

public function show($id)
    {
        $ticket = Ticketing::findOrFail($id);
        return view('ticketing.show', compact('ticket'));
    }
    
    public function edit($id)
    {
        $ticket = Ticketing::findOrFail($id);
        return view('ticketing.edit', compact('ticket'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'in:pending,open,solved'
        ]);
        
        $ticket = Ticketing::findOrFail($id);
        $ticket->update($request->only(['name', 'subject', 'description', 'status']));
        
        return redirect()->route('ticketing.index')->with('success', 'Tiket berhasil diperbarui!');
    }
    
public function destroy($id)
{
    $ticket = Ticketing::findOrFail($id);
    $ticket->delete();

    return redirect()->back()->with('success', 'Tiket berhasil dihapus.');
}


public function addToPriority($id)
{
    $ticket = Ticketing::with('user')->findOrFail($id);

    $exists = PrioritasTicket::where('id_user', $ticket->id_user)->first();
    if ($exists) {
        return back()->with('warning', 'User sudah ada di daftar prioritas.');
    }

    PrioritasTicket::create([
        'id_user' => $ticket->id_user,
        'name' => $ticket->name,
        'npk' => $ticket->user->npk ?? '-', 
        'added_at' => now(),
    ]);

    return back()->with('success', 'User berhasil ditambahkan ke daftar prioritas.');
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,in progress,solved,on the list,reject',
    ]);

    $ticket = Ticketing::findOrFail($id);
    $ticket->status = $request->input('status');
    $ticket->save();

    return redirect()->route('ticketing.index')->with('success', 'Status tiket berhasil diperbarui.');
}
}
