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

}
