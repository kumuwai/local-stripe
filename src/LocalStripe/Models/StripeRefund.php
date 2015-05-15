<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeRefund extends Eloquent
{
    protected $table = 'stripe_refunds';
    protected $guarded = [];
    public $timestamps = false;


    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'transaction_id' => $stripe->balance_transaction,
            'charge_id' => $stripe->charge,
            'receipt_number' => $stripe->receipt_number,
            'reason' => $stripe->reason,
            // 'description' => $stripe->description,
            'created_at' => $stripe->created,
        ]);

        foreach($stripe->metadata->__toArray() as $key=>$value)
            StripeMetadata::create(['stripe_id'=>$stripe->id, 'key'=>$key, 'value'=>$value]);

        return self::findOrFail($stripe->id);
    }

    public function charge()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCharge', 'charge_id');
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
