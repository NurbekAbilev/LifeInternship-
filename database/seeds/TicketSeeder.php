<?php

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketCategory;
use App\User;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $statuses = TicketStatus::all();
        $categories = TicketCategory::all();
        $users = User::all();

        factory(App\Models\Ticket::class, 20)->make()->each(function ($ticket)
            use ($statuses, $categories, $users) {
            $ticket->ticket_category = $categories->random()->id;
            $ticket->ticket_status = $statuses->random()->id;
            if ($ticket->status->id >= 3) {
                $ticket->admin_id = $users->random()->id;
            }
            $ticket->save();
        });

        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'john@test.com';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);
        $ticket->save();
    }
}
