<?php namespace Kumuwai\LocalStripe;

use PHPUnit_Framework_TestCase;
use Faker\Factory as Faker;
use Kumuwai\MockObject\MockObject;
use Mockery;


class TestCase extends PHPUnit_Framework_TestCase
{
    protected $test;
    protected $faker;

    public function __construct($name = NULL, array $data = array(), $dataName = '') 
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Faker::create();        
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function getFakeCustomerToStripe($options=[])
    {
        return [
            'email' => $this->faker->email,
            'description' => $this->faker->sentence,
            'metadata' => ['name' => $this->faker->name],
            'source' => $this->getFakeCardToStripe(),
            'charge' => $this->getFakeChargeToStripe(),
        ];
    }

    protected function getFakeCardToStripe()
    {
        $card = $this->getFakeCardData();
        $card['number'] = $this->faker->randomElement($this->sample_cards);
        return $card;
    }

    protected function getFakeChargeToStripe($options = [])
    {
        return array_merge([
            'amount' => $this->faker->numberBetween(100,12000),
            'currency' => 'usd',
            'metadata' => ['url'=>$this->faker->url],
        ], $options);
    }

    protected function getFakeDataToPush($options = [])
    {
        return array_merge([
            'source' => 'tok_xxx',
            'email' => $this->faker->email,
            'customer.description' => $this->faker->name,
            'customer.metadata' => ['client_id'=>$this->faker->numberBetween(11,99)],
            'name' => $this->faker->name,
            'address_line1' => $this->faker->streetAddress,
            'address_city' => $this->faker->city,
            'address_state' => $this->faker->state,
            'address_zip' => $this->faker->postcode,            
            'amount' => $this->faker->numberBetween(100, 20000),
            'currency' => 'usd',
            'charge.description' => 'charge #' . $this->faker->numberBetween(11,99),
            'charge.statement_descriptor' => 'Company charge #' . $this->faker->numberBetween(11,99),
        ], $options);
    }

    protected function getFakeCustomerFromStripe($options=[])
    {
        return MockObject::mock('MockCustomer',array_merge([
            'id' => 'cust_' . $this->faker->numberBetween(11,99),
            'email' => $this->faker->email,
            'description' => $this->faker->sentence,
            'livemode' => 'false',
            'default_source' => 'ch_' . $this->faker->numberBetween(11,99),
            'metadata' => $this->getFakeMetadataFromStripe(['name'=>$this->faker->name]),
            'sources' => $this->getFakeSourcesFromStripe(),
            'charges' => [$this->getFakeChargeFromStripe()],
            'created' => $this->faker->datetime,
        ], $options));
    }

    protected function getFakeSourcesFromStripe($options=[])
    {
        return MockObject::mock('MockSources',
            ['data'=>[$this->getFakeCardFromStripe($options)]]
        );
    }

    protected function getFakeCardFromStripe($options = [])
    {
        return MockObject::mock('MockCard', array_merge($this->getFakeCardData(), [
            'id' => 'card_' . $this->faker->numberBetween(11,99),
            'brand' => $this->faker->randomElement(['Visa', 'American Express', 'MasterCard', 
                'Discover', 'JCB', 'Diners Club', 'Unknown']),
            'last4' => $this->faker->numberBetween(0000,9999),
            'fingerprint' => $this->faker->uuid(),
            'funding' => 'credit',
            'address_line1_check' => 'true',
            'address_zip_check' => 'true',
            'cvc_check' => 'true',
            'customer' => 'cust_' .$this->faker->numberBetween(11,99),
            'name' => $this->faker->name,
        ], $options));
    }

    protected function getFakeCardData()
    {
        return [
            'object' => 'card',
            'exp_month' => $this->faker->numberBetween(1,12),
            'exp_year' => $this->faker->numberBetween(1,5) + date('Y'),
            'address_line1' => $this->faker->streetAddress,
            'address_city' => $this->faker->city,
            'address_state' => $this->faker->state,
            'address_zip' => $this->faker->postcode,
            'address_city' => '',
            'address_country' => '',
            'address_line2' => '',
            'country' => '',
        ];
    }

    protected function getFakeChargeFromStripe($options = [])
    {
        $charge = MockObject::mock('MockCharge', [
            'id' => 'ch_' . $this->faker->numberBetween(11,99),
            'source' => MockObject::mock('MockCardSource', [
                'id' => 'card_' . $this->faker->numberBetween(11,99),
                'customer' => 'cust_' . $this->faker->numberBetween(11,99),
            ]),
            'amount' => $this->faker->numberBetween(100,12000),
            'currency' => 'usd',
            'livemode' => 'false',
            'captured' => 'false',
            'paid' => 'false',
            'refunded' => 'false',
            'status' => Null,
            'amount_refunded' => Null,
            'description' => Null,
            'failure_code' => Null,
            'failure_message' => Null,
            'receipt_email' => Null,
            'receipt_number' => Null,
            'created' => Null,
            'metadata' => $this->getFakeMetadataFromStripe(),
            'balance_transaction' => $this->getFakeBalanceTransactionFromStripe(),
        ]);

        foreach($options as $key=>$value)
            $charge->$key = $value;

        return $charge;
    }

    protected function getFakeBalanceTransactionFromStripe($options = [])
    {
        return MockObject::mock('MockBalanceTransaction', [
            'id' => 'tx_'.$this->faker->numberBetween(11,99),
            'amount' => $this->faker->numberBetween(100,9900),
            'currency' => 'usd',
            'net' => $this->faker->numberBetween(100,9900),
            'fee' => $this->faker->numberBetween(10,990),
            'source' => 'ch_'.$this->faker->numberBetween(11,99),
            'created' => $this->faker->datetime(),
        ]);
    }

    protected function getFakeMetadataFromStripe($values = [])
    {
        return MockObject::mock('MockMetadata', ['__toArray' => $values]);
    }

    protected function getEmptyModel($options = [])
    {
        return MockObject::mock('MockEmptyModel', $options);
    }

    // Setup all mock objects attached to the connector
    // 
    // $this->connector->local('card')  
    //   returns a local_card_mock object ($this->local_card)
    protected function setupMockConnector()
    {
        $this->connector = MockObject::mock('Kumuwai\LocalStripe\Connector');
        foreach(['local'=>'local_','remote'=>'stripe_'] as $type=>$prefix) {
            foreach(['customer','card','charge','metadata','balance_transaction'] as $model) {
                $name = $prefix.$model;
                $this->$name = MockObject::mock($name.'_mock');
                $this->connector->shouldReceive($type)->byDefault()
                    ->with($model)->andReturn($this->$name);
            }
        }
    }

    protected function setupMockStripeCollection($type, $has_more, $items)
    {
        $data = [];
        foreach ($items as $item) {
            $method = "getFake{$type}FromStripe";
            $data[] = $this->$method($item);
        }

        return MockObject::mock('MockStripeCollection', compact('has_more','data'));
    }

    protected $sample_cards = [
        '4242424242424242',
        '4012888888881881',
        '4000056655665556',
        '5555555555554444',
        '5200828282828210',
        '5105105105105100',
        '378282246310005',
        '371449635398431',
        '6011111111111117',
        '6011000990139424',
        '30569309025904',
        '38520000023237',
        '3530111333300000',
        '3566002020360505',
    ];

}

