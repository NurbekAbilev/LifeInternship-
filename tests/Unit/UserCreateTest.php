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
        $user=new User;
        $user->name="Elnur";
        $user->email="elnur9924@gmail.com";
        $user->password= Hash::make('secret');
        $user->role= 0;
        $user->save();
        $this->tester->seeRecord('users', ['name' => 'Elnur','email' => 'elnur9924@gmail.com']);
    }
}