<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeCardTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeCard;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
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
        $this->assertEquals('addr_1', $test->address->id);
    }

    public function testHasMetadata()
    {
        $test = $this->test->find('card_1');
        $this->assertNotNull($test->metadata);
    }

    public function testDontCreateDuplicateObject()
    {
        $card = $this->getFakeCardFromStripe(['id'=>'card_1']);

        $test = $this->test->createFromStripe($card);

        $this->assertNotNull($test);
        $this->assertEquals('card_1', $test->id);
    }

    public function testCanCreateFromStripeObject()
    {
        $card = $this->getFakeCardFromStripe(['id'=>'card_394']);

        $test = $this->test->createFromStripe($card);

        $this->assertNotNull($test);
        $this->assertEquals('card_394', $test->id);
    }

}

