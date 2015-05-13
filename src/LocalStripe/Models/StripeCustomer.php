<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeCustomer extends Eloquent
{

    protected $table = 'stripe_customers';
    protected $guarded = [];
    public $timestamps = false;


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

        foreach($stripe->metadata->__toArray() as $key=>$value)
            StripeMetadata::create(['stripe_id'=>$stripe->id, 'key'=>$key, 'value'=>$value]);

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

    /**
     * Return metadata as attribute
     */
    public function __get($attribute)
    {
        if ($result = parent::getAttribute($attribute))
            return $result;

        if ($metadata = $this->metadata()->where('key', $attribute)->first())
            return $metadata->value;
    }

// Relationsips ---------------------------------------------------------------

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
