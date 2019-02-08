<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketStatus;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index_api()
    {
        $tickets = Ticket::with('status')
            ->with('category')
            ->with('admin')
            ->orderBy('updated_at', 'desc')
            ->orderBy('ticket_status')
            ->get();

        return $tickets;
    }
}
