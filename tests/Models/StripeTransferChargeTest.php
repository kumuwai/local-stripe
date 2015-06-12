<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeTransferChargeTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeTransferCharge;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
    }

    public function testExists() {}

    public function testReturnObjectIfWantedDuplicateCreated()
    {
        $c1 = $this->getFakeTransferBalanceTransactionFromStripe([
            'transfer'=>'tr_1', 'source'=>'ch_1'
        ]);

        $test = $this->test->createFromStripe($c1,'tr_1');

        $this->assertNotNull($test);
        $this->assertEquals('tr_1', $test->transfer_id);
        $this->assertEquals('ch_1', $test->charge_id);
    }

    public function testCanCreateFromStripeObject()
    {
        $c1 = $this->getFakeTransferBalanceTransactionFromStripe([
            'transfer'=>'tr_12', 'source'=>'ch_14'
        ]);

        $test = $this->test->createFromStripe($c1,'tr_12');

        $this->assertNotNull($test);
        $this->assertEquals('tr_12', $test->transfer_id);
        $this->assertEquals('ch_14', $test->charge_id);
    }

}
