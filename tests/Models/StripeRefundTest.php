<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeRefundTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeRefund;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
    }

    public function testClassExists() {}

    public function testCanFindRecord() 
    {
        $test = $this->test->find('re_1');
        $this->assertNotNull($test);
    }
    
    public function testHasBalance()
    {
        $test = $this->test->find('re_1');
        $this->assertNotNull($test->balance);
    }

    public function testHasCharge()
    {
        $test = $this->test->find('re_1');
        $this->assertNotNull($test->charge);
        $this->assertEquals('ch_1', $test->charge->id);
    }

    public function testHasMetadata()
    {
        $test = $this->test->find('re_1');
        $this->assertNotNull($test->metadata);
        $this->assertTrue(count($test->metadata)>0);
    }

    public function testReturnObjectIfWantedDuplicateCreated()
    {
        $c1 = $this->getFakeRefundFromStripe(['id'=>'re_1']);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('re_1', $test->id);
    }

    public function testCanCreateFromStripeObject()
    {
        $c1 = $this->getFakeRefundFromStripe([
            'id'=>'re_14',
        ]);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('re_14', $test->id);
    }

    public function testCanCreateMetadata()
    {
        $c1 = $this->getFakeRefundFromStripe([
            'id'=>'ch_14',
            'metadata'=> $this->getFakeMetadataFromStripe(['foo'=>'bar']),
        ]);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test->metadata);
        $this->assertEquals('bar', $test->metadata[0]->value);
    }

}

