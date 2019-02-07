<?php 

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
    }

    /*
     *  Работает ли аутентификация
     */
    public function checkAuthentication(FunctionalTester $I)
    {
        $I->amLoggedAs(['email' => 'admin@chocolife.me', 'password' => 'secret']);
    }
}
