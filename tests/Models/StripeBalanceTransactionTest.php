<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeBalanceTransactionTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeBalanceTransaction;
    }

    public function testClassExists() {}
    
    public function testHasCharge()
    { 
        $test = $this->test->find('tr_1');
        $this->assertNotNull($test->charge);
    }

}

