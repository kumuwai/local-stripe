<?php namespace Kumuwai\LocalStripe;


class FetcherTest extends TestCase
{
    public function testExists() 
    {
        $test = new Fetcher;
    }

    public function testShouldFetchCustomerRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Customer', true, [['id'=>'cust_1'],['id'=>'cust_2']]);
        $c2 = $this->setupMockStripeCollection('Customer', false, [['id'=>'cust_3']]);
        $this->stripe_customer->shouldReceive('all')->times(2)->andReturn($c1,$c2);

        $test = new Fetcher($this->connector);
        $result = $test->fetchCustomerRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
    }

    public function testShouldLoadCustomerRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Customer', true, [['id'=>'cust_1'],['id'=>'cust_2']]);
        $c2 = $this->setupMockStripeCollection('Customer', false, [['id'=>'cust_3']]);
        $this->stripe_customer->shouldReceive('all')->times(2)->andReturn($c1,$c2);
        $this->local_customer->shouldReceive('createFromStripe')->times(3)->andReturn('x','y','z');

        $test = new Fetcher($this->connector);
        $result = $test->loadCustomerRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
        $this->assertEquals(['x','y','z'], $result);
    }

    public function testShouldFetchChargeRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Charge', true, [['id'=>'ch_1'],['id'=>'ch_2']]);
        $c2 = $this->setupMockStripeCollection('Charge', false, [['id'=>'ch_3']]);
        $this->stripe_charge->shouldReceive('all')->times(2)->andReturn($c1,$c2);

        $test = new Fetcher($this->connector);
        $result = $test->fetchChargeRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
    }

    public function testShouldLoadChargeRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Charge', true, [['id'=>'ch_1'],['id'=>'ch_2']]);
        $c2 = $this->setupMockStripeCollection('Charge', false, [['id'=>'ch_3']]);
        $this->stripe_charge->shouldReceive('all')->times(2)->andReturn($c1,$c2);
        $this->stripe_balance_transaction->shouldReceive('retrieve')->times(3)->andReturn('x');
        $this->local_charge->shouldReceive('createFromStripe')->times(3)->andReturn('x');
        $this->local_balance_transaction->shouldReceive('createFromStripe')->times(3)->andReturn('x');

        $test = new Fetcher($this->connector);
        $result = $test->loadChargeRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);        
    }

    public function testShouldFetchTransferRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Transfer', true, [['id'=>'tr_1'],['id'=>'tr_2']]);
        $c2 = $this->setupMockStripeCollection('Transfer', false, [['id'=>'tr_3']]);
        $this->stripe_transfer->shouldReceive('all')->times(2)->andReturn($c1,$c2);

        $test = new Fetcher($this->connector);
        $result = $test->fetchTransferRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);

    }

    public function testShouldLoadTransferRecords()
    {
        $this->setupMockConnector();
        $c1 = $this->setupMockStripeCollection('Transfer', true, 
            [['id'=>'trx_1'],['id'=>'trx_2']]);
        $c2 = $this->setupMockStripeCollection('Transfer', false, 
            [['id'=>'trx_3']]);
        $this->stripe_transfer->shouldReceive('all')
            ->times(2)->andReturn($c1,$c2);
        $this->stripe_balance_transaction->shouldReceive('all')
            ->times(3)->andReturn(
                $this->setupMockStripeCollection('BalanceTransaction', false, [['id'=>'trx_1']])
            );
        $this->local_transfer->shouldReceive('createFromStripe')
            ->times(3)->andReturn($this->getFakeTransferFromStripe());
        $this->local_transfer_charge->shouldReceive('createFromStripe')
            ->times(3)->andReturn('x');

        $test = new Fetcher($this->connector);
        $result = $test->loadTransferRecords();

        $this->assertNotNull($result);
        $this->assertCount(3, $result);
    }

    public function testCanFetchAllDataFromStripe()
    {
        $this->setupMockConnector();

        $customers = $this->setupMockStripeCollection('Charge', false, [['id'=>'cust_1']]);
        $charges = $this->setupMockStripeCollection('Charge', false, [['id'=>'ch_1']]);
        $transfers = $this->setupMockStripeCollection('Transfer', false, [['id'=>'tr_1']]);
        $transactions = $this->setupMockStripeCollection('BalanceTransaction', false, [['id'=>'txn_1']]);

        $this->stripe_customer->shouldReceive('all')->times(1)->andReturn($customers);
        $this->stripe_charge->shouldReceive('all')->times(1)->andReturn($charges);
        $this->stripe_transfer->shouldReceive('all')->times(1)->andReturn($transfers);

        $this->stripe_balance_transaction->shouldReceive('all')->andReturn($transactions);
        $this->stripe_balance_transaction->shouldReceive('retrieve')->andReturn('x');

        $this->local_customer->shouldReceive('createFromStripe')->andReturn('x');
        $this->local_metadata->shouldReceive('createFromStripe')->andReturn('x');
        $this->local_card->shouldReceive('createFromStripe')->andReturn('x');
        $this->local_charge->shouldReceive('createFromStripe')->andReturn('x');
        $this->local_balance_transaction->shouldReceive('createFromStripe')->andReturn('x');
        $this->local_transfer->shouldReceive('createFromStripe')->andReturn($this->getFakeTransferFromStripe());
        $this->local_transfer_charge->shouldReceive('createFromStripe')->andReturn('x');

        $test = new Fetcher($this->connector);
        $result = $test->fetch();

        $this->assertNotNull($result);
        $this->assertNotNull($result['customers'][0]);
        $this->assertNotNull($result['charges'][0]);
        $this->assertNotNull($result['transfers'][0]);
    }


}

