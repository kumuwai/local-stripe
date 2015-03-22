<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeBalanceTransaction extends Eloquent
{

    public function charge()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCharge', 'charge_id');
    }

}
