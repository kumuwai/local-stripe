<?php namespace Kumuwai\LocalStripe\Models;


class StripeCustomer extends StripeBaseModel
{
    protected $table = 'stripe_customers';

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'livemode' => ($stripe->livemode == 'true'),
            'description' => $stripe->description,
            'email' => $stripe->email,
            'default_card' => $stripe->default_source,
            'created_at' => $stripe->created,
        ]);

        self::createMetadata($stripe);

        foreach($stripe->sources->data as $source)
            StripeCard::createFromStripe($source);

        return self::findOrFail($stripe->id);
    }

    /**
     * Return the card with the given last 4 numbers
     */
    public function card($last4)
    {
        return $this->cards()->where('last4', $last4)->first();
    }


// Relationsips ---------------------------------------------------------------

    public function cards()
    {
        return $this->hasMany(
            self::MY_NAMESPACE.'StripeCard', 
            'customer_id'
        );
    }
    
    public function charges()
    {
        return $this->hasMany(
            self::MY_NAMESPACE.'StripeCharge', 
            'customer_id'
        );
    }

    public function metadata()
    {
        return $this->hasMany(
            self::MY_NAMESPACE.'StripeMetadata', 
            'stripe_id'
        );
    }

}
