<?php namespace Kumuwai\LocalStripe;

use Kumuwai\MockObject\MockObject;

/**
 * Generate mock Stripe data. This includes data to send to Stripe,
 * and data that will be returned from Stripe.
 *
 * Any class calling this trait must include a $faker property
 * compatible with Faker\Factory
 */
trait MockStripeObjectsTrait
{
    protected function getFakeCustomerToStripe($options=[])
    {
        return array_merge([
            'email' => $this->faker->optional(0.4)->email,
            'description' => $this->faker->optional(0.3)->sentence,
            'metadata' => ['name' => $this->faker->name],
        ], $options);
    }

    protected function getFakeCardToStripe($options = [])
    {
        return ['source' => $this->getFakeCardData(array_merge([
            'number' => $this->faker->randomElement($this->sample_cards)
        ], $options))];
    }

    protected function getFakeChargeToStripe($options = [])
    {
        return array_merge([
            'amount' => $this->faker->numberBetween(100,12000),
            'currency' => 'usd',
            'metadata' => ['url'=>$this->faker->url],
        ], $options);
    }

    protected function getFakeRefundToStripe($options = [])
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
            'email' => $this->faker->optional(0.3)->email,
            'customer.description' => $this->faker->optional(0.3)->name,
            'customer.metadata' => ['client_id'=>$this->faker->numberBetween(11,99)],
            'name' => $this->faker->optional(0.4)->name,
            'address_line1' => $this->faker->optional(0.3)->streetAddress,
            'address_city' => $this->faker->optional(0.3)->city,
            'address_state' => $this->faker->optional(0.3)->state,
            'address_zip' => $this->faker->optional(0.3)->postcode,            
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
            'email' => $this->faker->optional(0.3)->email,
            'description' => $this->faker->optional(0.3)->sentence,
            'livemode' => 'false',
            'default_source' => 'ch_' . $this->faker->numberBetween(11,99),
            'metadata' => $this->getFakeMetadataFromStripe(['name'=>$this->faker->name]),
            'sources' => $this->getFakeSourcesFromStripe(),
            'charges' => [$this->getFakeChargeFromStripe()],
            'created' => $this->faker->datetime,
        ], $options));
    }

    protected function getFakeSourcesFromStripe($cardOptions=[], $sourceOptions=[])
    {
        return MockObject::mock('MockSources', array_merge([
            'data'=>[$this->getFakeCardFromStripe($cardOptions)],
        ], $sourceOptions));
    }

    protected function getFakeCardFromStripe($options = [])
    {
        return MockObject::mock('MockCard', array_merge($this->getFakeCardData(), [
            'id' => 'card_' . $this->faker->numberBetween(11,99),
            'brand' => $this->faker->randomElement(['Visa', 'American Express', 'MasterCard', 
                'Discover', 'JCB', 'Diners Club', 'Unknown']),
            'last4' => $this->faker->numberBetween(0000,9999),
            'fingerprint' => $this->faker->md5,
            'funding' => 'credit',
            'address_line1_check' => 'true',
            'address_zip_check' => 'true',
            'cvc_check' => 'true',
            'customer' => 'cust_' .$this->faker->numberBetween(11,99),
            'name' => $this->faker->optional(0.3)->name,
            'metadata' => $this->getFakeMetadataFromStripe(),
        ], $options));
    }

    protected function getFakeCardData($options = [])
    {
        return array_merge([
            'object' => 'card',
            'exp_month' => $this->faker->numberBetween(1,12),
            'exp_year' => $this->faker->numberBetween(1,5) + date('Y'),
            'address_line1' => $this->faker->optional(0.3)->streetAddress,
            'address_city' => $this->faker->optional(0.3)->city,
            'address_state' => $this->faker->optional(0.3)->state,
            'address_zip' => $this->faker->optional(0.3)->postcode,
            'address_city' => '',
            'address_country' => '',
            'address_line2' => '',
            'country' => '',
        ], $options);
    }

    protected function getFakeChargeFromStripe($options = [])
    {
        return MockObject::mock('MockCharge', array_merge([
            'id' => 'ch_' . $this->faker->numberBetween(11,99),
            'source' => $this->getFakeCardFromStripe(),
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
            'refunds' => [],
        ], $options));
    }

    protected function getFakeRefundFromStripe($options = [])
    {
        return MockObject::mock('MockCharge', array_merge([
            'id' => 're_' . $this->faker->numberBetween(11,99),
            'amount' => $this->faker->numberBetween(100,12000),
            'currency' => 'usd',
            'created' => null,
            'object' => 'refund',
            'balance_transaction' => $this->getFakeBalanceTransactionFromStripe(),
            'metadata' => $this->getFakeMetadataFromStripe(),
            'charge' => 'ch_' . $this->faker->numberBetween(11,99),
            'receipt_number' => null,
            'description' => null,
            'reason' => null,
        ], $options));
    }


    protected function getFakeBalanceTransactionFromStripe($options = [])
    {
        return MockObject::mock('MockBalanceTransaction', array_merge([
            'id' => 'tx_'.$this->faker->numberBetween(11,99),
            'amount' => $this->faker->numberBetween(100,9900),
            'currency' => 'usd',
            'net' => $this->faker->numberBetween(100,9900),
            'fee' => $this->faker->numberBetween(10,990),
            'source' => 'ch_'.$this->faker->numberBetween(11,99),
            'created' => $this->faker->datetime(),
        ], $options));
    }

    protected function getFakeMetadataFromStripe($options = [])
    {
        return MockObject::mock('MockMetadata', ['__toArray' => $options]);
    }

    protected function getEmptyModel($options = [])
    {
        return MockObject::mock('MockEmptyModel', $options);
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
