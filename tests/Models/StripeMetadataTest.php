<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;
use Mockery;


class StripeMetadataTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeMetadata;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
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

    public function testCanCreate()
    {
        $test = $this->test->create(['stripe_id'=>'a_1','key'=>'foo','value'=>'bar']);
        $this->assertEquals('a_1', $test->stripe_id);
    }

}

