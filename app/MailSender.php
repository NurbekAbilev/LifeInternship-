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
            $messageRaw = $messageRaw . route('tickets.show', ['hash' => $ticket->hash]);
            Mail::raw($messageRaw, function ($message) use ($ticket) {
                $message->from(env("MAIL_USERNAME"));
                $message->to($ticket->email)->subject("Тикет $ticket->id");
            });
            \Log::info('Почта отправлена, тикет - ' . $ticket);
            return true;
        } catch (\Exception $e) {
            \Log::error($e->getMessage() . '   ' . $ticket);
        }
        return false;
    }


}
