<?php namespace Kumuwai\LocalStripe;

use PHPUnit_Framework_TestCase;
use Faker\Factory as Faker;
use Kumuwai\MockObject\MockObject;


class TestCase extends PHPUnit_Framework_TestCase
{
    use MockStripeObjectsTrait;

    protected $test;
    protected $faker;

    private $objects = [
        'balance_transaction',
        'card',
        'charge',
        'customer',
        'metadata',
        'transfer',
        'transfer_charge',
    ];

    public function __construct($name = NULL, array $data = array(), $dataName = '') 
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Faker::create();        
    }

    public function tearDown()
    {
        MockObject::close();
    }

    /**
     * Setup all mock objects attached to the connector
     *
     *   $this->connector->local('card')  
     *      returns a local_card_mock object (eg, $this->local_card) 
     */
    protected function setupMockConnector()
    {
        $this->connector = MockObject::mock('Kumuwai\LocalStripe\Connector');
        foreach(['local'=>'local_','remote'=>'stripe_'] as $type=>$prefix) {
            foreach($this->objects as $model) {
                $name = $prefix.$model;
                $this->$name = MockObject::mock($name.'_mock');
                $this->connector->shouldReceive($type)->byDefault()
                    ->with($model)->andReturn($this->$name);
            }
        }
    }

}

