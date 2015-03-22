<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeCharge extends Eloquent
{
    protected $table = 'stripe_charges';
    
    public function card()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCard', 'card_id');
    }

    public function customer()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCustomer', 'customer_id');
    }

    public function balance()
    {
        return $this->hasOne('Kumuwai\LocalStripe\Models\StripeBalanceTransaction', 'charge_id');
    }

    public function metadata()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeMetadata', 'stripe_id');
    }

}
