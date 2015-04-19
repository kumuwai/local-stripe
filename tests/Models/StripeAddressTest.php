<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeAddressTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeAddress;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
    }

    public function testClassExists() {}
    
    public function testHasCard()
    {
        $test = $this->test->find('addr_1');
        $this->assertNotNull($test->card);
    }

    public function testEmptyAddressMeansNoObjectCreated()
    {
        $card = $this->getEmptyModel([
            'address_city' => '',
            'address_country' => '',
            'address_line1' => '',
            'address_line2' => '',
            'address_state' => '',
            'address_zip' => '',
            'country' => '',
        ]);

        $test = $this->test->createFromStripe($card);

        $this->assertNull($test);
    }

    public function testCanCreateFromStripeCardObject()
    {
        $card = $this->getFakeCardFromStripe([
            'id'=>'card_4',
            'address_city' => 'Foo',
        ]);

        $test = $this->test->createFromStripe($card);

        $this->assertNotNull($test);
        $this->assertEquals('card_4',$test->stripe_id);
    }

}

