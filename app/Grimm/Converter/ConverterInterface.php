<?php

namespace Grimm\Converter;

interface ConverterInterface {

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

    /**
     * Converts given data into an array
     * @param mixed $data
     * @return array
     */
    public function convert($data);

    /**
     * Returns parsed and converted data as array
     * @return array
     * @throws \Exception
     */
    public function toArray();

    /**
     * Returns parsed and converted data as json
     * @return string
     * @throws \Exception
     */
    public function toJson();

}
