<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeCardTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeCard;
    }

    public function testClassExists() {}
    
    public function testHasCustomer()
    {
        $test = $this->test->find('card_1');
        $this->assertNotNull($test->customer);
    }

    public function testHasCharges()
    {
        $test = $this->test->find('card_1');
        $this->assertNotNull($test->charges);
    }

    public function testHasAddress()
    {
        $test = $this->test->find('card_1');
        $this->assertNotNull($test->address);
    }

    public function testHasMetadata()
    {
        $test = $this->test->find('card_1');
        $this->assertNotNull($test->metadata);
    }

}

