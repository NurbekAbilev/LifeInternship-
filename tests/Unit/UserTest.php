<?php 

use App\User;

class UserTest extends \Codeception\Test\Unit
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
    public function testUserCreate()
    {
        $this->tester->dontSeeRecord('users', ['name' => 'John', 'email' => 'john@test.com']);

        $user = new User;
        $user->name = 'John';
        $user->email = 'john@test.com';
        $user->password = Hash::make('secret');
        $user->role = 0;
        $user->save();

        $this->tester->seeRecord('users', ['name' => 'John', 'email' => 'john@test.com']);
    }

    public function testUserUpdate()
    {
        $this->tester->seeRecord('users', ['email' => 'admin@chocolife.me']);

        $user = User::where('email', 'admin@chocolife.me')->first();
        $user->email = "john@test.com";
        $user->save();

        $this->assertEquals('john@test.com', $user->email);
        $this->tester->seeRecord('users', ['email' => 'john@test.com']);
        $this->tester->dontSeeRecord('users', ['email' => 'admin@chocolife.me']);
    }

    public function testUserDelete()
    {
        $user = new User;
        $user->name = 'John';
        $user->email = 'john@test.com';
        $user->password = Hash::make('secret');
        $user->role = 0;
        $user->save();

        $this->tester->seeRecord('users', ['email' => 'john@test.com']);

        $user->delete();

        $this->tester->dontSeeRecord('users', ['email' => 'jhon@test.com']);
    }
}
