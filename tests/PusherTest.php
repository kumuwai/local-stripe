<?php namespace Kumuwai\LocalStripe;

use Mockery;


class PusherTest extends TestCase
{
    public function setUp()
    {
        $this->test = new Pusher;
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testExists() {}

}
