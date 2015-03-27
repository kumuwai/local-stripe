<?php namespace Kumuwai\LocalStripe;

use Kumuwai\MockObject\MockObject;


class LocalStripeTest extends TestCase
{
    public function testExists() 
    {
        $test = new LocalStripe;
    }

    public function testCanGetRemoteStripeObjects()
    {
        $connector = MockObject::mock('Kumuwai\LocalStripe\Connector');
        $connector->shouldReceive('remote')->once()->with('customer')->andReturn('x');
        $test = new LocalStripe($connector);

        $this->assertEquals('x', $test->remote('customer'));
    }

    public function testCanGetLocalStripeObjects()
    {
        $connector = MockObject::mock('Kumuwai\LocalStripe\Connector');
        $connector->shouldReceive('local')->once()->with('customer')->andReturn('x');
        $test = new LocalStripe($connector);

        $this->assertEquals('x', $test->local('customer'));
    }

    public function testCanFetchRecordsFromStripeServer()
    {
        $fetcher = MockObject::mock('Kumuwai\LocalStripe\Fetcher');
        $fetcher->shouldReceive('fetch')->once()->with(['foo'=>'bar'])->andReturn(['x'=>'y']);
        $test = new LocalStripe(Null, Null, $fetcher);

        $results = $test->fetch(['foo'=>'bar']);
        $this->assertEquals(['x'=>'y'], $results);
    }

    public function testCanPushChargeToStripeServer()
    {
        $pusher = MockObject::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('charge')->once()->with(['foo'=>'bar'])->andReturn(['x'=>'y']);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->charge(['foo'=>'bar']);

        $this->assertEquals(['x'=>'y'], $result);
    }

    public function testCanCreateCustomer()
    {
        $customer = MockObject::mock('MockCustomer');
        $customer->id = 'cust_1';

        $pusher = MockObject::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('createCustomer')->once()->with(['foo'=>'bar'])->andReturn($customer);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->create(['foo'=>'bar']);

        $this->assertEquals($customer, $result);
    }

    public function testCanCreateCustomerWithCharge()
    {
        $customer = MockObject::mock('MockCustomer', ['cards', 'id'=>'cust_1']);

        $pusher = MockObject::mock('Kumuwai\LocalStripe\Pusher');
        $pusher->shouldReceive('createCustomer')->once()
            ->with(['foo'=>'bar'])->andReturn($customer);
        $pusher->shouldReceive('charge')->once()
            ->with(['customer'=>'cust_1','foo'=>'bar'])->andReturn(['x'=>'y']);

        $test = new LocalStripe(Null, $pusher, Null);
        $result = $test->chargeCustomer(['foo'=>'bar']);

        $this->assertEquals(['x'=>'y'], $result);
    }

    public function testGiveDirectAccessToComponents()
    {
        $connector = MockObject::mock('Kumuwai\LocalStripe\Connector');
        $pusher = MockObject::mock('Kumuwai\LocalStripe\Pusher');
        $fetcher = MockObject::mock('Kumuwai\LocalStripe\Fetcher');

        $test = new LocalStripe($connector, $pusher, $fetcher);

        $this->assertInstanceOf('Kumuwai\LocalStripe\Connector', $test->getConnector());
        $this->assertInstanceOf('Kumuwai\LocalStripe\Pusher', $test->getPusher());
        $this->assertInstanceOf('Kumuwai\LocalStripe\Fetcher', $test->getFetcher());
    }
}

