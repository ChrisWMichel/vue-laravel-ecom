<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['name', 'discount', 'valid_until'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function checkIfValid()
    {
        if($this->valid_until > Carbon::now()) {
            return true;
        }
        return false;
    }
}
