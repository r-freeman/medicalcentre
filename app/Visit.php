<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Add attributes to a Visit model
     *
     * @param array $attributes
     */
    public function addAttributes(Array $attributes)
    {
        foreach ($attributes as $key => $val) {
            $this->{$key} = $val;
        }
    }
}
