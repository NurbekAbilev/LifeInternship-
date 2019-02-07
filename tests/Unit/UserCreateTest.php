<?php 
use App\User;

class UserCreateTest extends \Codeception\Test\Unit
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
    public function testCreate()
    {
        $user = new User;
        $user->name = "Elnur";
        $user->email = "elnur9924@gmail.com";
        $user->password = Hash::make('secret');
        $user->role = 0;
        $user->save();
        $this->tester->seeRecord('users', ['name' => 'Elnur', 'email' => 'elnur9924@gmail.com']);
    }
    public function testupdateUser()
    {
        $user = User::where('email', 'admin@chocolife.me')->first();
        $user->email = "Adilet9924@gmail.com";
        $user->save();
        $user = User::where('email', 'Adilet9924@gmail.com')->first();
        $this->assertEquals('Adilet9924@gmail.com', $user->email);
        $this->tester->dontSeeRecord('users', ['email' => 'admin@chocolife.me']);
        $this->tester->seeRecord('users', ['email' => 'Adilet9924@gmail.com']);
    }
    public function testDelete()
    {
        User::where('email', 'John elnur9924@gmail.com')->delete();
        $this->tester->dontSeeRecord('users', ['email' => 'elnur9924@gmail.com']);
    }
}