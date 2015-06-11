<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


abstract class StripeBaseModel extends Eloquent
{
    const MY_NAMESPACE = 'Kumuwai\LocalStripe\Models\\';

    protected $guarded = [];
    public $timestamps = false;


    protected static function createMetadata($stripe)
    {
        foreach($stripe->metadata->__toArray() as $key=>$value)
            StripeMetadata::create([
                'stripe_id'=>$stripe->id, 
                'key'=>$key, 
                'value'=>$value
            ]);
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

}
