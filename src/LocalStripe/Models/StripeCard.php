<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait as SoftDeletes;


class StripeCard extends StripeBaseModel
{
    use SoftDeletes;

    protected $table = 'stripe_cards';
    protected $dates = ['deleted_at'];

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'brand' => $stripe->brand,
            'exp_month' => $stripe->exp_month,
            'exp_year' => $stripe->exp_year,
            'fingerprint' => $stripe->fingerprint,
            'funding' => $stripe->funding,
            'last4' => $stripe->last4,
            'address_line1_check' => $stripe->address_line1_check,
            'address_zip_check' => $stripe->address_zip_check,
            'cvc_check' => $stripe->cvc_check,
            'customer_id' => $stripe->customer,
            'name' => $stripe->name,
        ]);

        self::createMetadata($stripe);
        StripeAddress::createFromStripe($stripe);

        return self::findOrFail($stripe->id);
    }

    public function customer()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCustomer',
            'customer_id'
        );
    }

    public function address()
    {
        return $this->hasOne(
            self::MY_NAMESPACE.'StripeAddress',
            'stripe_id'
        );
    }

    public function charges()
    {
        return $this->hasMany(
            self::MY_NAMESPACE.'StripeCharge',
            'card_id'
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
