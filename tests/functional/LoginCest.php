<?php 

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('email', 'admin@chocolife.me');
        $I->fillField('password', 'secret');
        $I->click('Login');
        $I->amOnPage('home');
    }
}
