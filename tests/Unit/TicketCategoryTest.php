<?php 

use Validator;
use App\Models\TicketCategory;

class TicketCategoryTest extends \Codeception\Test\Unit
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
    public function testEmptyName()
    {
        $category = new TicketCategory;

        $category->name = null;

        $this->assertFalse($category->validate());
    }

    public function testName()
    {
        $category = new TicketCategory;

        $category->name = 'Category';

        $this->assertTrue($category->validate());
    }
}