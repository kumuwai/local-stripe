<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeBalanceTransaction extends Eloquent
{
    protected $table = 'stripe_balance_transactions';
    protected $guarded = [];
    public $timestamps = false;

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        $new = self::create([
            'id' => $stripe->id,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'net' => $stripe->net,
            'fee' => $stripe->fee,
            'charge_id' => $stripe->source,
            'created_at' => $stripe->created,
        ]);

        return self::find($stripe->id);
    }

    public function charge()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCharge', 'charge_id');
    }

}
