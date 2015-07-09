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
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'metadata' => ['url'=>$this->faker->url],
        ], $options);
    }

    protected function getFakeRefundToStripe($options = [])
    {
        return array_merge([
            'amount' => $this->getFakeAmount(),
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
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'charge.description' => 'charge #' . $this->faker->numberBetween(11,99),
            'charge.statement_descriptor' => 'Company charge #' . $this->faker->numberBetween(11,99),
        ], $options);
    }

    protected function getFakeCustomerFromStripe($options=[])
    {
        return MockObject::mock('MockCustomer',array_merge([
            'object' => 'customer',
            'created' => $this->getFakeDate(),
            'id' => $this->getFakeId('cust'),
            'livemode' => false,
            'description' => $this->faker->optional(0.3)->sentence,
            'email' => $this->faker->optional(0.3)->email,
            'delinquent' => false,
            'metadata' => $this->getFakeMetadataFromStripe(['name'=>$this->faker->name]),
            'subscriptions' => MockObject::mock('MockSubscription',[]),
            'discount' => null,
            'account_balance' => 0,
            'currency' => null,
            'sources' => $this->getFakeSourcesFromStripe(),
            'default_source' => $this->getFakeId('ch'),
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
            'id' => $this->getFakeId('card'),
            'object' => 'card',
            'last4' => $this->faker->numberBetween(0000,9999),
            'brand' => $this->faker->randomElement(['Visa', 'American Express', 'MasterCard', 
                'Discover', 'JCB', 'Diners Club', 'Unknown']),
            'funding' => 'credit',
            'fingerprint' => $this->faker->md5,            
            'name' => $this->faker->optional(0.3)->name,
            'cvc_check' => 'pass',
            'address_line1_check' => null,
            'address_zip_check' => null,
            'dynamic_last4' => null,
            'metadata' => $this->getFakeMetadataFromStripe(),
            'customer' => $this->getFakeId('cust'),
        ], $options));
    }

    protected function getFakeCardData($options = [])
    {
        return array_merge([
            'exp_month' => $this->faker->numberBetween(1,12),
            'exp_year' => $this->faker->numberBetween(1,5) + date('Y'),
            'country' => '',
            'address_line1' => $this->faker->optional(0.3)->streetAddress,
            'address_line2' => $this->faker->optional(0.1)->secondaryAddress,
            'address_city' => $this->faker->optional(0.3)->city,
            'address_state' => $this->faker->optional(0.3)->state,
            'address_zip' => $this->faker->optional(0.3)->postcode,
            'address_country' => '',
        ], $options);
    }

    protected function getFakeChargeFromStripe($options = [])
    {
        return MockObject::mock('MockCharge', array_merge([
            'id' => $this->getFakeId('ch'),
            'object' => 'charge',
            'created' => $this->getFakeDate(),
            'livemode' => false,
            'paid' => false,
            'status' => 'succeeded',
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'refunded' => false,
            'source' => $this->getFakeCardFromStripe(),
            'captured' => true,
            'balance_transaction' => $this->getFakeId('txn'),
            'failure_message' => Null,
            'failure_code' => Null,
            'amount_refunded' => 0,
            'customer' => $this->getFakeId('cus'),
            'invoice' => Null,
            'description' => Null,
            'dispute' => Null,
            'metadata' => $this->getFakeMetadataFromStripe(),
            'statement_descriptor' => Null,
            'fraud_details' => [],
            'receipt_email' => Null,
            'receipt_number' => Null,
            'authorization_code' => uniqid(),
            'shipping' => Null,
            'destination' => Null,
            'application_fee' => Null,
            'refunds' => [],
        ], $options));
    }

    protected function getFakeRefundFromStripe($options = [])
    {
        return MockObject::mock('MockCharge', array_merge([
            'id' => $this->getFakeId('re'),
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'created' => $this->getFakeDate(),
            'object' => 'refund',
            'balance_transaction' => $this->getFakeId('txn'),
            'metadata' => $this->getFakeMetadataFromStripe(),
            'charge' => $this->getFakeId('ch'),
            'receipt_number' => null,
            'description' => null,
            'reason' => null,
        ], $options));
    }

    protected function getFakeBalanceTransactionFromStripe($options = [])
    {
        return MockObject::mock('MockBalanceTransaction', array_merge([
            'id' => $this->getFakeId('txn'),
            'object' => 'balance_transaction',
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'net' => $this->getFakeAmount(),
            'type' => 'charge',
            'created' => $this->getFakeDate(),
            'status' => 'pending',
            'fee' => $this->getFakeAmount(),
            'fee_details' => [],  // TODO: Flesh this out
            'source' => $this->getFakeId('ch'),
            'description' => null,
            'sourced_transfers' => [], // TODO: flesh this out
        ], $options));
    }

    protected function getFakeTransferFromStripe($options = [])
    {
        return MockObject::mock('MockTransfer', array_merge([
            'id' => $this->getFakeId('tr'),
            'object' => 'transfer',
            'created' => $this->getFakeDate(),
            'date' => $this->getFakeDate('-2 days','+4 days'),
            'livemode' => false,
            'amount' => $this->getFakeAmount(),
            'currency' => 'usd',
            'reversed' => false,
            'status' => 'paid',
            'type' => 'bank_account',
            'reversals' => [],
            'balance_transaction' => $this->getFakeId('txn'),
            'bank_account' => [],  // TODO: flesh this out
            'destination' => $this->getFakeId('ba'), 
            'description' => null, 
            'failure_message' => null,
            'failure_code' => null,
            'amount_reversed' => 0,
            'metadata' => [],
            'statement_descriptor' => null,
            'recipient' => null,
            'source_transaction' => null,
            'application_fee' => null,
        ], $options));
    }

    protected function getFakeTransferBalanceTransactionFromStripe($options = [])
    {
        return $this->getFakeBalanceTransactionFromStripe(
            array_merge([
                'available_on' => $this->getFakeDate(),
                'transfer' => $this->getFakeId('tr'),
            ], $options)
        );
    }

    protected function getFakeMetadataFromStripe($options = [])
    {
        return MockObject::mock('MockMetadata', ['__toArray' => $options]);
    }

    protected function getEmptyModel($options = [])
    {
        return MockObject::mock('MockEmptyModel', $options);
    }

    protected function getFakeAmount($min=100,$max=12000)
    {
        return $this->faker->numberBetween($min,$max);
    }

    protected function getFakeDate($start = '-6 days', $end = 'now')
    {
        return $this->faker->dateTimeBetween($start, $end)
            ->getTimestamp();
    }

    protected function getFakeId($type,$start=11,$end=99)
    {
        return $type.'_'.$this->faker->numberBetween($start,$end);
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
