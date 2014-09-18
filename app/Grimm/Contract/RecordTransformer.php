<?php

namespace Grimm\Contract;

interface RecordTransformer {

    /**
     * Transform record data to target data
     * @param $data
     * @return mixed
     */
    public function transform($data);

} 