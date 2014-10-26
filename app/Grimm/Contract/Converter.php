<?php

namespace Grimm\Contract;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;

interface Converter extends JsonableInterface, ArrayableInterface {

    /**
     * Sets source for parser
     * @param string $source
     * @throws \InvalidArgumentException
     */
    public function setSource($source);


    /**
     * Sets filter for returned data
     * @param array $filter
     */
    public function setFilter(array $filter);

    /**
     * Parses given source file
     * @return yield array
     */
    public function parse();
}
