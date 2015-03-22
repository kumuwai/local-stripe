<?php namespace Kumuwai\LocalStripe;

use PHPUnit_Framework_TestCase;
use Mockery;


class TestCase extends PHPUnit_Framework_TestCase
{
    protected $test;

    public function tearDown()
    {
        Mockery::close();
    }

}

