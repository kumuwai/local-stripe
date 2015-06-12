<?php 

use Illuminate\Database\Schema\Blueprint;
use Kumuwai\LocalStripe\Laravel\BaseMigration;


class CreateTransferTables extends BaseMigration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createStripeTransfers();
        $this->createStripeTransferCharges();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('stripe_transfers');
        $this->schema->drop('stripe_transfer_charges');
    }

    private function createStripeTransfers()
    {
        $this->schema->create('stripe_transfers', function(Blueprint $table) {
            $table->string('id', 32)->primaryKey();
            $table->string('destination_id', 32)->index()->nullable();
            $table->integer('amount')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status', 8)->nullable();
            $table->timestamp('deposited_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    private function createStripeTransferCharges()
    {
        $this->schema->create('stripe_transfer_charges', function(Blueprint $table) {
            $table->string('id', 64)->primaryKey();
            $table->string('transfer_id', 32)->index();
            $table->string('charge_id', 32)->index();
            $table->string('transaction_id', 32)->index()->nullable();
            $table->integer('amount')->nullable();
            $table->string('currency',3)->nullable();
            $table->integer('net')->nullable();
            $table->integer('fee')->nullable();
            $table->timestamp('available_at')->index()->nullable();
            $table->timestamp('created_at')->index()->nullable();
        });
    }

}

