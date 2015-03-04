<?php

namespace Grimm\OutputTransformer;


use Illuminate\Support\Contracts\ArrayableInterface;

class ArrayOutput implements Output {

    public function transform($data)
    {
        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        return ['data' => $data];
    }
}