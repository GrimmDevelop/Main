<?php

namespace Grimm\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Location extends Eloquent
{
    protected $table = 'locations';

    public function lettersFrom()
    {
        return $this->hasMany('Grimmm\Models\Letter', 'from_id');
    }

    public function lettersTo()
    {
        return $this->hasMany('Grimmm\Models\Letter', 'to_id');
    }

    public function letters()
    {
        $from = $this->lettersFrom;
        $to = $this->lettersTo;

        $from = array_combine($from->modelKeys(), $from->toArray());
        $to = array_combine($to->modelKeys(), $to->toArray());

        return array_merge($from, $to);
    }
}
