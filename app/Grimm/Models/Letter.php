<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter\Information;
use App;

class Letter extends Eloquent
{
    protected $table = 'letters';


    public function informations($code = null)
    {
        $hasMany = $this->hasMany(Information::class);

        if ($code !== null) {
            $hasMany->where('code', $code);
        }

        return $hasMany;
    }

    public function codes()
    {
        return App::make(Information::class)->codes();
    }

    public function scopeWithCodes($codes)
    {
        $validCodes = $this->codes();
        if (!is_array($codes)) {
            $codes = array($codes);
        }

        foreach ($codes as $code) {
            if (!in_array($validCodes, $code)) {
                throw new \InvalidArgumentException('Invalid letter information code: ' . $code);
            }
        }

        return $this->with(array('informations' => function ($query) use ($codes) {
            $query->whereIn('code', $codes);
        }));
    }

    public function senders()
    {
        return $this->belongsToMany(Person::class, 'letter_sender');
    }

    public function receivers()
    {
        return $this->belongsToMany(Person::class, 'letter_receiver');
    }

    public function from()
    {
        return $this->belongsTo(Location::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(Location::class, 'to_id');
    }
}
