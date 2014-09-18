<?php

namespace Grimm\Converter;

use Grimm\Contract\Converter;
use Grimm\Contract\RecordTransformer;

class Location implements Converter
{
    protected $cache = null;
    protected $filter = null;
    protected $source = null;

    /**
     * @var RecordTransformer
     */
    private $recordTransformer;

    public function __construct(RecordTransformer $recordTransformer)
    {
        $this->recordTransformer = $recordTransformer;
    }

    /**
     * Sets source for parser
     * @param string $source
     * @throws \InvalidArgumentException
     */
    public function setSource($source)
    {
        if (!file_exists($source)) {
            throw new \InvalidArgumentException('Invalid source (file not found)');
        }
        $this->source = $source;
        $this->cache = null;
    }

    /**
     * Sets filter for returned data
     * @param array $filter
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Parses given source file
     * @return yield array
     */
    public function parse()
    {
        $this->cache = array();

        $handle = fopen($this->source, "r");
        while ($record = fgetcsv($handle, 0, "	")) {
            $data = $this->recordTransformer->transform($record);

            if ($data != null) {
                $this->cache[] = $data;
                yield $data;
            }
        }
        fclose($handle);
    }

    /**
     * Returns parsed and converted data as array
     * @return array
     * @throws \Exception
     */
    public function toArray()
    {
        if (is_null($this->cache)) {
            throw new \Exception("cache is null, run parse() first!");
        }

        return $this->cache;
    }

    /**
     * Returns parsed and converted data as json
     * @param int $options
     * @throws \Exception
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }
}