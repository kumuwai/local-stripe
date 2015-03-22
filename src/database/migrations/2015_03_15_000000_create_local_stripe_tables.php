<?php 

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;


class CreateLocalStripeTables extends Migration 
{

	public function __construct()
	{
		$this->schema = Capsule::schema();
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->createStripeAddresses();
		$this->createStripeBalanceTransactions();
		$this->createStripeCards();
		$this->createStripeCharges();
		$this->createStripeCustomers();
		$this->createStripeMetadata();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->schema->drop('stripe_addresses');
		$this->schema->drop('stripe_balance_transactions');
		$this->schema->drop('stripe_cards');
		$this->schema->drop('stripe_charges');
		$this->schema->drop('stripe_customers');
		$this->schema->drop('stripe_metadata');
	}

	private function createStripeAddresses()
	{
		$this->schema->create('stripe_addresses', function(Blueprint $table) {
			$table->string('id', 32)->primaryKey();
	        $table->string('address_city', 40)->nullable();
	        $table->string('address_country', 20)->nullable();
	        $table->string('address_line1', 80)->nullable();
	        $table->string('address_line2', 80)->nullable();
	        $table->string('address_state', 20)->nullable();
	        $table->string('address_zip', 12)->nullable();
	        $table->string('country', 2)->nullable();
		});
	}

	private function createStripeBalanceTransactions()
	{
		$this->schema->create('stripe_balance_transactions', function(Blueprint $table) {
            $table->string('id', 32)->primaryKey();
            $table->integer('amount')->nullable();
            $table->string('currency',3)->nullable();
            $table->integer('net')->nullable();
            $table->integer('fee')->nullable();
            $table->string('charge_id',32)->index()->nullable();
            $table->timestamp('created_at')->nullable();
        });
	}

	private function createStripeCards()
	{
		$this->schema->create('stripe_cards', function(Blueprint $table) {
			$table->string('id', 32)->primaryKey();
	        $table->string('brand', 20)->nullable();
	        $table->string('exp_month', 2)->nullable();
	        $table->string('exp_year', 4)->nullable();
	        $table->string('fingerprint', 20)->nullable();
	        $table->string('funding', 8)->nullable();
	        $table->string('last4', 4)->nullable();
	        $table->string('address_id', 32)->nullable();
	        $table->boolean('address_line1_check')->nullable();
	        $table->boolean('address_zip_check')->nullable();
	        $table->boolean('cvc_check')->nullable();
	        $table->string('customer_id', 32)->index()->nullable();
	        $table->string('name', 80)->nullable();
            $table->timestamp('created_at')->nullable();
		});
	}

	private function createStripeCharges()
	{
		$this->schema->create('stripe_charges', function(Blueprint $table) {
			$table->string('id', 32)->primaryKey();
	        $table->string('card_id', 32)->index()->nullable();
	        $table->string('customer_id', 32)->index()->nullable();
	        $table->boolean('livemode')->nullable();
	        $table->integer('amount')->nullable();
	        $table->boolean('captured')->nullable();
	        $table->string('currency', 3)->nullable();
	        $table->boolean('paid')->nullable();
	        $table->boolean('refunded')->nullable();
	        $table->string('status', 10)->nullable();
	        $table->integer('amount_refunded')->nullable();
	        $table->string('description', 120)->nullable();
	        $table->string('failure_code', 80)->nullable();
	        $table->string('failure_message', 80)->nullable();
	        $table->string('receipt_email', 80)->nullable();
	        $table->string('receipt_number', 32)->nullable();
            $table->timestamp('created_at')->nullable();
		});
	}

	private function createStripeCustomers()
	{
		$this->schema->create('stripe_customers', function(Blueprint $table) {
			$table->string('id', 32)->primaryKey();
			$table->boolean('livemode')->nullable();
			$table->string('description',80)->nullable();
			$table->string('email',80)->index()->nullable();
			$table->string('default_card',32)->index()->nullable();
            $table->timestamp('created_at')->nullable();
		});
	}

	private function createStripeMetadata()
	{
		$this->schema->create('stripe_metadata', function(Blueprint $table) {
			$table->string('id', 32)->primaryKey();
        	$table->string('stripe_id', 32)->index();
        	$table->string('key', 40)->index();
        	$table->string('value', 500);
		});
	}

}

