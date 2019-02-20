<?php

use App\Models\Ticket;

class TicketTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testValidEmail()
    {
        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'john@test.com';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);

        $this->assertTrue($ticket->validate());
    }

    public function testInvalidEmail()
    {
        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'INVALID';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);

        $this->assertFalse($ticket->validate());
    }

    public function testTicketCreate()
    {
        $this->tester->dontSeeRecord('ticket', ['full_name' => 'John Doe']);

        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'john@test.com';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);
        $ticket->save();

        $this->tester->seeRecord('ticket', ['full_name' => 'John Doe']);
    }

    public function testTicketUpdate()
    {
        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'john@test.com';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);
        $ticket->save();
        $this->tester->seeRecord('ticket', ['full_name' => 'John Doe']);

        $ticket = Ticket::where('full_name', 'John Doe')->first();
        $ticket->full_name = 'John Test';
        $ticket->save();

        $this->assertEquals('John Test', $ticket->full_name);
        $this->tester->seeRecord('ticket', ['full_name' => 'John Test']);
        $this->tester->dontSeeRecord('ticket', ['full_name' => 'John Doe']);
    }

    public function testTicketDelete()
    {
        $ticket = new Ticket;
        $ticket->full_name = 'John Doe';
        $ticket->email = 'john@test.com';
        $ticket->phone_num = '+77777777777';
        $ticket->description = 'description';
        $ticket->ticket_category = 1;
        $ticket->ticket_status = 1;
        $ticket->hash = md5($ticket->id . date('Y-m-d H:i:s') . $ticket->full_name . $ticket->email);
        $ticket->save();
        $this->tester->seeRecord('ticket', ['full_name' => 'John Doe']);

        $ticket->delete();
        $this->tester->dontSeeRecord('ticket', ['full_name' => 'John Test']);
    }
}
