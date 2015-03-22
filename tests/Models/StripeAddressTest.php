<?php namespace Kumuwai\LocalStripe\Models;

use Kumuwai\LocalStripe\TestCase;


class StripeAddressTest extends TestCase
{

    public function setUp()
    {
        $this->test = new StripeAddress;
    }

    public function testClassExists() {}
    
    public function testHasCard()
    {
        $test = $this->test->find('addr_1');
        $this->assertNotNull($test->card);
    }

}

