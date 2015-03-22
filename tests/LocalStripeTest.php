<?php namespace Kumuwai\LocalStripe;

use Mockery;


class LocalStripeTest extends TestCase
{

    public function testExists() 
    {
        $test = new LocalStripe;
    }

    public function testCanGetRemoteStripeObjects()
    {
        $connector = Mockery::mock('Kumuwai\LocalStripe\Connector');
        $connector->shouldReceive('remote')->once()->with('customer')->andReturn('x');
        $test = new LocalStripe($connector);

        $this->assertEquals('x', $test->remote('customer'));
    }

    public function testCanGetLocalStripeObjects()
    {
        $connector = Mockery::mock('Kumuwai\LocalStripe\Connector');
        $connector->shouldReceive('local')->once()->with('customer')->andReturn('x');
        $test = new LocalStripe($connector);

        $this->assertEquals('x', $test->local('customer'));
    }

    public function testCanFetchRecordsFromStripeServer()
    {
        $fetcher = Mockery::mock('Kumuwai\LocalStripe\Fetcher');
        $fetcher->shouldReceive('fetch')->once()->with(['foo'=>'bar'])->andReturn(['x'=>'y']);
        $test = new LocalStripe(Null, Null, $fetcher);

        $results = $test->fetch(['foo'=>'bar']);
        $this->assertEquals(['x'=>'y'], $results);
    }

    public function testCanPushChargeToStripeServer()
    {
        $pusher = Mockery::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('charge')->once()->with(['foo'=>'bar'])->andReturn(['x'=>'y']);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->charge(['foo'=>'bar']);

        $this->assertEquals(['x'=>'y'], $result);
    }

    public function testCanCreateCustomer()
    {
        $customer = Mockery::mock('MockCustomer');
        $customer->id = 'cust_1';

        $pusher = Mockery::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('createCustomer')->once()->with(['foo'=>'bar'])->andReturn($customer);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->create(['foo'=>'bar']);

        $this->assertEquals($customer, $result);
    }

    public function testCanCreateCustomerWithCharge()
    {
        $customer = Mockery::mock('MockCustomer');
        $customer->id = 'cust_1';

        $pusher = Mockery::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('createCustomer')->once()->with(['foo'=>'bar'])->andReturn($customer);
        $pusher->shouldReceive('charge')->once()->with(['source'=>'cust_1','foo'=>'bar'])->andReturn(['x'=>'y']);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->chargeCustomer(['foo'=>'bar']);

        $this->assertEquals(['x'=>'y'], $result);
    }

}

