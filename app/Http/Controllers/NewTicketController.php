<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\Ticket;
use Auth;
use Validator;
use Hash;
use App\MailSender;

class NewTicketController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'categories' => TicketCategory::orderBy('id', 'desc')->get(),
        ];
        return view('new-ticket', $data);
    }

    public function form(Request $request)
    {
        \Log::debug($request->input());

        $validator = Validator::make($request->all(), [
            'full-name' => 'required',
            'email' => 'email',
            'phone' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $ticket = new Ticket;
        $ticket->full_name = $request->get('full-name');
        $ticket->email = $request->get('email');
        $ticket->phone_num = $request->get('phone');
        $ticket->description = $request->get('description');
        $ticket->ticket_category = $request->get('category');
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . $ticket->full_name . $ticket->email);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $file->store('attachments'); //  storage/app/attachments
            $file_name = $file->hashName();
            $ticket->file_path = $file_name;
        }
        $hash = $ticket->hash;
        $hashLink = "http://127.0.0.1:8000/ticket/$hash";
        $messageRaw = "Спасибо за обращение в службу технической поддержки ChocoLife. Можете отслеживать ваш запрос здесь: $hashLink .";
        $mailTo = Auth::user()->email;
        $ticket->user_id = Auth::user()->id;
        $ticket->save();
        MailSender::send($messageRaw, $mailTo, $hash);
        return redirect('/ticket/' . $hash);
    }
}
