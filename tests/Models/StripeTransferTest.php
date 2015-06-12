<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeTransferTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeTransfer;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
    }

    public function testExists() {}

    public function testHasCharges()
    {
        $test = $this->test->find('tr_1');
        $this->assertNotNull($test->charges);
    }

    public function testReturnObjectIfWantedDuplicateCreated()
    {
        $c1 = $this->getFakeTransferFromStripe(['id'=>'tr_1']);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('tr_1', $test->id);
    }

    public function testCanCreateFromStripeObject()
    {
        $c1 = $this->getFakeTransferFromStripe([
            'id'=>'tr_14',
        ]);

        $test = $this->test->createFromStripe($c1);

        $this->assertNotNull($test);
        $this->assertEquals('tr_14', $test->id);
    }


}
