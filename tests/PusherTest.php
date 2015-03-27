<?php namespace Kumuwai\LocalStripe;

use Mockery;


class PusherTest extends TestCase
{
    protected $connector;

    public function setUp()
    {
        $this->setupMockConnector();

        $this->test = new Pusher($this->connector, new ParameterParser);
    }

    public function testExists() {}

    public function testCanCreateCustomer()
    {
        $cust = $this->getFakeCustomerFromStripe([
            'id'=>'cust_1',
            'sources' => $this->getFakeSourcesFromStripe([],['create'=>'x']),
        ]);

        $this->stripe_customer->shouldReceive('create')
            ->once()->with(Mockery::subset(['source'=>'tk_foo']))
            ->andReturn($cust);
        $this->local_customer->shouldReceive('createFromStripe')
            ->once()->with($cust)
            ->andReturn('x');
        $params = $this->getFakeDataToPush(['source'=>'tk_foo']);

        $customer = $this->test->createCustomer($params);

        $this->assertNotNull($customer);
        $this->assertEquals('x', $customer);
    }

    public function testCanCreateCharge()
    {
        $ch = $this->getFakeChargeFromStripe(['id'=>'ch_1']);
        $this->stripe_charge->shouldReceive('create')
            ->once()->with(Mockery::subset(['source'=>'tk_foo']))
            ->andReturn($ch);
        $this->local_charge->shouldReceive('createFromStripe')
            ->once()->with($ch)
            ->andReturn('x');
        $params = $this->getFakeDataToPush(['source'=>'tk_foo']);

        $charge = $this->test->charge($params);

        $this->assertNotNull($charge);
        $this->assertEquals('x', $charge);
    }


}
