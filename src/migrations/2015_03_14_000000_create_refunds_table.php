<?php 

use Illuminate\Database\Schema\Blueprint;
use Kumuwai\LocalStripe\Laravel\BaseMigration;


class CreateRefundsTable extends BaseMigration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $this->schema->create('stripe_refunds', function(Blueprint $table) {
            $table->string('id', 32)->primaryKey();
            $table->integer('amount')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('transaction_id', 32)->index()->nullable();
            $table->string('charge_id', 32)->index()->nullable();
            $table->string('receipt_number', 32)->index()->nullable();
            $table->string('reason', 20)->index()->nullable();
            $table->string('description', 80)->index()->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->drop('stripe_refunds');
    }

}

