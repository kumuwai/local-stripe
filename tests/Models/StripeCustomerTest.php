<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeCustomerTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeCustomer;
    }

    public function testClassExists() {}
    
    public function testHasCards()
    {
        $test = $this->test->find('cust_1');
        $this->assertNotNull($test->cards);
    }

    public function testHasCharges()
    {
        $test = $this->test->find('cust_1');
        $this->assertNotNull($test->charges);
    }

    public function testHasMetadata()
    {
        $test = $this->test->find('cust_1');
        $this->assertNotNull($test->metadata);        
        $this->assertTrue(count($test->metadata)>0);
    }

}

