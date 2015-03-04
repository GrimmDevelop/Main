<?php

namespace Grimm\Values;


use Carbon\Carbon;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;

class DateRange implements JsonableInterface, ArrayableInterface {
    /**
     * @var Carbon
     */
    private $min;

    /**
     * @var Carbon
     */
    private $max;

    public function __construct(Carbon $minDate, Carbon $maxDate)
    {
        // TODO: Add validation that min date is not larger than max date
        $this->min = $minDate;
        $this->max = $maxDate;
    }

    /**
     * @return Carbon
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return Carbon
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $min = $this->min->format('Y-m-d');
        $max = $this->max->format('Y-m-d');

        return ['min' => $min, 'max' => $max];
    }
}