<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeCustomerTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeCustomer;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
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

    public function testDontCreateDuplicateObject()
    {
        $c1 = $this->getFakeCustomerFromStripe(['id'=>'cust_1']);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('cust_1', $test->id);
    }

    public function testCanCreateFromStripeObject()
    {
        $c1 = $this->getFakeCustomerFromStripe(['id'=>'cust_999']);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('cust_999', $test->id);
    }

    public function testCanCreateMetadata()
    {
        $c1 = $this->getFakeCustomerFromStripe([
            'metadata' => $this->getFakeMetadataFromStripe(['foo'=>'bar']),
        ]);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test->metadata);
        $this->assertEquals('bar', $test->metadata[0]->value);
        $this->assertEquals('bar', $test->foo);
        $this->assertNull($test->something_missing);
    }

    public function testCanCreateCard()
    {
        $c1 = $this->getFakeCustomerFromStripe([
            'id'=>'cust_4',
            'sources' => $this->getFakeSourcesFromStripe([
                'id'=>'card_22','customer'=>'cust_4','last4'=>'2201'
            ]),
        ]);

        $test = $this->test->createFromStripe($c1);

        $this->assertTrue(count($test->cards)>0);
        $this->assertEquals('card_22', $test->cards[0]->id);
        $this->assertEquals('card_22', $test->card('2201')->id);
    }

}

