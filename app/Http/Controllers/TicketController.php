<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketStatus;
use App\Models\Comment;
use Mail;
use Auth;
use App\MailSender;

class TicketController extends Controller
{

    /*
     *  Авторизация только для create, store, show, attachment
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'show', 'attachment']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::with('status')->with('category')->orderBy('updated_at', 'desc')->orderBy('ticket_status')->get();
        $ticketCategories = TicketCategory::all();
        $ticketStatuses = TicketStatus::all();

        return view('home', [
            'tickets' => $tickets,
            'categories' => $ticketCategories,
            'statuses' => $ticketStatuses
        ]);
    }

    public function autoUpdate()
    {
        $tickets = Ticket::with('status')
            ->with('category')
            ->with('admin')
            ->orderBy('updated_at', 'desc')
            ->orderBy('ticket_status')
            ->get();

        return $tickets;
    }

    public function search(Request $request)
    {
        $ticketCategories = TicketCategory::all();
        $ticketStatuses = TicketStatus::all();

        $tickets = Ticket::query();

        if ($request->filled('category')) {
            $tickets->where('ticket_category', $request->get('category'));
        }

        if ($request->filled('status')) {
            $tickets->where('ticket_status', $request->get('status'));
        }

        if ($request->filled('query')) {
            $tickets->where(function ($query) use ($request) {
                $query->where('full_name', 'like', '%' . $request->get('query') . '%')
                    ->orWhere('email', 'like', '%' . $request->get('query') . '%')
                    ->orWhere('phone_num', 'like', '%' . $request->get('query') . '%')
                    ->orWhere('description', 'like', '%' . $request->get('query') . '%');
            });
        }

        $tickets = $tickets->orderBy('created_at', 'desc')->orderBy('ticket_status')->get();

        $request->flash();

        return view('search', [
            'tickets' => $tickets,
            'categories' => $ticketCategories,
            'statuses' => $ticketStatuses
        ]);
    }

    public function comment(Request $request, Ticket $ticket)
    {
        $comment = new Comment;

        $attributes = request()->validate([
            'content' => 'required'
        ]);

        $comment->content = $attributes['content'];
        $comment->ticket_id = $ticket->id;
        $comment->user_id = Auth::check() ? Auth::user()->id : null;
        $comment->admin_only = request('admin_only') ? true : false;
        $comment->save();

        if ($comment->admin_only == false) {
            if (Auth::check() && Auth::user()->isAdmin()) {
                $ticket->ticket_status = 4;
                $ticket->admin_id = Auth::user()->id;
                $ticket->answered_at = date('d.m.Y H:i:s');
                $ticket->save();
                $messageRaw = "На ваш тикет ответили! Ответ можете посмотреть здесь: ";
                MailSender::send($messageRaw, $ticket);
            } else {
                $ticket->ticket_status = 4;
                $ticket->answer_time = date('d.m.Y H:i:s');
                $ticket->save();
            }
        }

        return back();
    }

    public function process(Request $request, Ticket $ticket)
    {
        $ticket->ticket_status = 3;
        $ticket->admin_id = Auth::user()->id;
        $ticket->save();

        return back();
    }

    public function close(Request $request, Ticket $ticket)
    {
        $ticket->ticket_status = 5;
        $ticket->admin_id = Auth::user()->id;
        $ticket->save();

        return back();
    }

    public function attachment(Ticket $ticket)
    {
        $file_path = storage_path() . '/app/attachments/' . $ticket->file_path;
        if (!file_exists($file_path)) {
            abort(404); // не должен случиться, но кто знает...
        }
        return response()->file($file_path);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = TicketCategory::orderBy('id', 'desc')->get();
        return view('new-ticket', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'full-name' => 'required',
            'email' => 'email',
            'phone' => 'required',
            'category' => 'required',
            'description' => 'required',
            'attachment' => 'file|max:10240' // 10 mb
        ]);

        $ticket = new Ticket;
        $ticket->full_name = $request->get('full-name');
        $ticket->email = $request->get('email');
        $ticket->phone_num = $request->get('phone');
        $ticket->description = $request->get('description');
        $ticket->ticket_category = $request->get('category');
        $ticket->ticket_status = 1;
        $mytime = date('Y-m-d H:i:s');
        $ticket->hash = md5($ticket->id . $mytime . $ticket->full_name . $ticket->email);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $file->store('attachments'); //  storage/app/attachments
            $file_name = $file->hashName();
            $ticket->file_path = $file_name;
        }
        $messageRaw = "Спасибо за обращение в службу поддержки ChocoLife. Можете отслеживать ваш запрос здесь: ";
        $ticket->save();
        MailSender::send($messageRaw, $ticket);

        return redirect()->route('tickets.show', ['hash' => $ticket->hash]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket)
    {
        if (Auth::check() && Auth::user()->isAdmin() && $ticket->ticket_status == 1) {
            $ticket->ticket_status = 2;
            $ticket->save();
        }
        return view('ticket', ['ticket' => $ticket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //todo
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ticket)
    {
        //todo
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //todo
        abort(404);
    }
}
