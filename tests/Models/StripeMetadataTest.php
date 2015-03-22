<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeMetadataTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeMetadata;
    }

    public function testClassExists() {}
    
    public function testHasCustomer()
    {
        $test = $this->test->find('meta_1');
        $this->assertNotNull($test->customer);
        $this->assertEquals('cust_1', $test->customer->id);
    }

    public function testHasCard()
    {
        $test = $this->test->find('meta_3');
        $this->assertNotNull($test->card);
        $this->assertEquals('card_1', $test->card->id);
    }

    public function testHasCharge()
    {
        $test = $this->test->find('meta_4');
        $this->assertNotNull($test->charge);
        $this->assertEquals('ch_1', $test->charge->id);
    }

}

