<?php namespace Kumuwai\LocalStripe\Models;


class StripeTransfer extends StripeBaseModel
{
    protected $table = 'stripe_transfers';
    protected $pivotFields = [
            'transfer_id',
            'charge_id',
            'transaction_id',
            'amount',
            'currency',
            'net',
            'fee',
            'available_at',
            'created_at',
    ];

    public function charges()
    {
        return $this->belongsToMany(
            self::MY_NAMESPACE.'StripeCharge',
            'stripe_transfer_charges',
            'transfer_id',
            'charge_id'
        )->withPivot($this->pivotFields);
    }

}
