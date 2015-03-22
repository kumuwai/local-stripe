<?php namespace Kumuwai\LocalStripe;

use Mockery;


class FetcherTest extends TestCase
{
    public function setUp()
    {
        $this->test = new Fetcher(null);
    }

    public function testExists() {}

    public function testCanFetchAllDataFromStripe()
    {
        $test = $this->test->fetch();
    }

}
