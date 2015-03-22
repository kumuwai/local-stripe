<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeChargeTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeCharge;
    }

    public function testClassExists() {}
    
    public function testHasCard()
    {
        $test = $this->test->find('ch_1');
        $this->assertNotNull($test->card);
    }

    public function testHasBalance()
    {
        $test = $this->test->find('ch_1');
        $this->assertNotNull($test->balance);
    }

    public function testHasCustomer()
    {
        $test = $this->test->find('ch_1');
        $this->assertNotNull($test->customer);
    }

    public function testHasMetadata()
    {
        $test = $this->test->find('ch_1');
        $this->assertNotNull($test->metadata);
        $this->assertTrue(count($test->metadata)>0);
    }
}

