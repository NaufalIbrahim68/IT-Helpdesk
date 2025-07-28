<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticketing;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    
public function index()
{
    $onTheListTickets = Ticketing::where('status', 'on the list')->count();
    $pendingTickets = Ticketing::where('status', 'pending')->count();
    $solvedTickets = Ticketing::where('status', 'solved')->count();
    $withoutAgentTickets = Ticketing::whereNull('assigned_to')->count();
       $rejectTickets = Ticketing::where('status', 'reject')->count();

    // Ambil jumlah solved per hari
   $solvedPerDay = Ticketing::select(
        DB::raw("CONVERT(date, created_at) as date"),
        DB::raw("COUNT(*) as total")
    )
    ->where('status', 'solved')
    ->groupBy(DB::raw("CONVERT(date, created_at)"))
    ->orderBy(DB::raw("CONVERT(date, created_at)"), 'asc')
    ->get();

    // Siapkan array label dan data
    $chartLabels = $solvedPerDay->pluck('date')->toArray();
    $chartData = $solvedPerDay->pluck('total')->toArray();

    return view('admin.dashboard', compact(
        'onTheListTickets',
        'pendingTickets',
        'solvedTickets',
        'withoutAgentTickets',
        'chartLabels',
        'chartData',
         'rejectTickets' 
    ));
}

}
