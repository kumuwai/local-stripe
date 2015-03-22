<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeCustomer extends Eloquent
{
    public function cards()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeCard', 'customer_id');
    }
    
    public function charges()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeCharge', 'customer_id');
    }

    public function metadata()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeMetadata', 'stripe_id');
    }

}
