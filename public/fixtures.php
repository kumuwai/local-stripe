<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;


class TestSeeder extends Seeder 
{
    private $capsule;

    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->loadSeedDataForRelationships();
    }

    private function loadSeedDataForRelationships()
    {
        $this->capsule->table('stripe_customers')
            ->insert(['id'=>'cust_1','default_card'=>'card_1','email'=>'foo@bar.com']);
        $this->capsule->table('stripe_customers')
            ->insert(['id'=>'cust_2']);

        $this->capsule->table('stripe_cards')
            ->insert(['id'=>'card_1','customer_id'=>'cust_1']);
        $this->capsule->table('stripe_cards')
            ->insert(['id'=>'card_2']);

        $this->capsule->table('stripe_addresses')
            ->insert(['id'=>'addr_1','stripe_id'=>'card_1']);
        $this->capsule->table('stripe_addresses')
            ->insert(['id'=>'addr_2']);

        $this->capsule->table('stripe_charges')
            ->insert(['id'=>'ch_1','card_id'=>'card_1','customer_id'=>'cust_1']);
        $this->capsule->table('stripe_charges')
            ->insert(['id'=>'ch_2']);

        $this->capsule->table('stripe_refunds')
            ->insert(['id'=>'re_1','charge_id'=>'ch_1','transaction_id'=>'tr_1']);
        $this->capsule->table('stripe_refunds')
            ->insert(['id'=>'re_2']);

        $this->capsule->table('stripe_transfers')
            ->insert(['id'=>'tr_1','destination_id'=>'ba_1']);
        $this->capsule->table('stripe_transfers')
            ->insert(['id'=>'tr_2']);

        $this->capsule->table('stripe_transfer_charges')
            ->insert(['transfer_id'=>'tr_1','charge_id'=>'ch_1','transaction_id'=>'tx_1']);
        $this->capsule->table('stripe_transfer_charges')
            ->insert(['transfer_id'=>'tr_2','charge_id'=>'ch_2']);

        $this->capsule->table('stripe_balance_transactions')
            ->insert(['id'=>'tr_1', 'charge_id'=>'ch_1']);
        $this->capsule->table('stripe_balance_transactions')
            ->insert(['id'=>'tr_2']);
        $this->capsule->table('stripe_balance_transactions')
            ->insert(['id'=>'tr_3', 'charge_id'=>'re_1']);

        $this->capsule->table('stripe_metadata')
            ->insert(['id'=>'meta_1','stripe_id'=>'cust_1','key'=>'key1','value'=>'val1']);
        $this->capsule->table('stripe_metadata')
            ->insert(['id'=>'meta_3','stripe_id'=>'card_1','key'=>'key1','value'=>'val1']);
        $this->capsule->table('stripe_metadata')
            ->insert(['id'=>'meta_4','stripe_id'=>'ch_1','key'=>'key1','value'=>'val1']);
        $this->capsule->table('stripe_metadata')
            ->insert(['id'=>'meta_4','stripe_id'=>'re_1','key'=>'key1','value'=>'val1']);
    }

}
