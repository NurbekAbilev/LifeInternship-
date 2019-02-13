<?php 
use App\Models\Ticket;
use App\MailSender;

class MailSenderTest extends \Codeception\Test\Unit
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
    public function testSendingValidMail()
    {
        $ticket = Ticket::where('id',1)->first();
        $this->assertTrue(MailSender::send("Test message", $ticket));
    }
    public function testSendingInvalidMail()
    {
        $ticket = Ticket::where('id',1)->first();
        $ticket->email="invalid";
        $ticket->save();
        $this->assertFalse(MailSender::send("Test message", $ticket));
    }
    
}