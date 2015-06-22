<?php 

use Illuminate\Database\Schema\Blueprint;
use Kumuwai\LocalStripe\Laravel\BaseMigration;


class AddDeletedAtToStripeCardsTable extends BaseMigration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->table('stripe_cards', function(Blueprint $table){
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->table('stripe_cards', function(Blueprint $table){
            $table->dropColumn('deleted_at');
        });

    }

}
