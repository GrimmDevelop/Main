<?php

namespace Grimm\Models\Letter;

use Grimm\Models\Location;
use Illuminate\Database\Eloquent\Model as Eloquent;

use Grimm\Models\Letter;

class Import extends Eloquent
{
    protected $table = 'letter_imports';

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function sendLocations()
    {
        return $this->belongsToMany(Location::class, 'letter_location_send');
    }

    public function receiveLocations()
    {
        return $this->belongsToMany(Location::class, 'letter_location_recv');
    }

    public function getSendersAttribute($value)
    {
        return json_decode($value);
    }

    public function setSendersAttribute($value)
    {
        $this->attributes['senders'] = json_encode($value);
    }

    public function getReceiversAttribute($value)
    {
        return json_decode($value);
    }

    public function setReceiversAttribute($value)
    {
        $this->attributes['receivers'] = json_encode($value);
    }

    public function getDrAttribute($value)
    {
        return json_decode($value);
    }

    public function setDrAttribute($value)
    {
        $this->attributes['dr'] = json_encode($value);
    }

    public function getKonzeptAttribute($value)
    {
        return json_decode($value);
    }

    public function setKonzeptAttribute($value)
    {
        $this->attributes['konzept'] = json_encode($value);
    }

    public function getAbschriftAttribute($value)
    {
        return json_decode($value);
    }

    public function setAbschriftAttribute($value)
    {
        $this->attributes['abschrift'] = json_encode($value);
    }

    public function getAuktkatAttribute($value)
    {
        return json_decode($value);
    }

    public function setAuktkatAttribute($value)
    {
        $this->attributes['auktkat'] = json_encode($value);
    }

    public function getZusatzAttribute($value)
    {
        return json_decode($value);
    }

    public function setZusatzAttribute($value)
    {
        $this->attributes['zusatz'] = json_encode($value);
    }
}
