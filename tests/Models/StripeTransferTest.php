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

}
