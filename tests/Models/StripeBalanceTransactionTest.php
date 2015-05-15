<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeBalanceTransactionTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeBalanceTransaction;
        $this->test->getConnection()->beginTransaction();
    }

    public function tearDown()
    {
        $this->test->getConnection()->rollBack();
    }

    public function testClassExists() {}

    public function testHasCharge()
    { 
        $test = $this->test->find('tr_1');
        $this->assertNotNull($test->charge);
    }

    public function testHasRefund()
    { 
        $test = $this->test->find('tr_3');
        $this->assertNotNull($test->refund);
    }

    public function testAttemptToCreateDuplicateObjectShouldReturnOriginal()
    {
        $tr = $this->getEmptyModel(['id'=>'tr_1']);

        $test = $this->test->createFromStripe($tr);

        $this->assertNotNull($test);
        $this->assertEquals('tr_1', $test->id);
    }

    public function testCanCreateFromStripeObject()
    {
        $tr = $this->getEmptyModel([
            'id' => 'tr_4',
            'amount' => '',
            'currency' => '',
            'net' => '',
            'fee' => '',
            'source' => 'ch_8',
            'created' => '',
        ]);

        $test = $this->test->createFromStripe($tr);

        $this->assertNotNull($test);
        $this->assertEquals('tr_4', $test->id);
    }

}

