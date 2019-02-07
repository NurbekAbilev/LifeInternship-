<?php
namespace App;

use App\Models\Ticket;
use Mail;
use Auth;

class MailSender
{

    public static function send($messageRaw, $ticket)
    {
        try {
            $hashLink = route('ticket.index', ['hash' => $ticket->hash]);
            $category = $ticket->category->name;
            $mailFrom = "lifeintern@mail.ru";
            $mailTo = $ticket->email;
            Mail::raw($messageRaw . $hashLink, function ($message) use ($mailFrom, $mailTo, $ticket) {
                $message->from($mailFrom);
                $message->to($mailTo)->subject("Тикет $ticket->id");
            });
            Log::info('Email was send', ['ticket' => $ticket->id]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }


}
