<?php

namespace Grimm\Converter;

use Grimm\Contract\Converter;
use Grimm\Contract\RecordTransformer;
use XBase\Table;

class Person implements Converter {

    protected $cache = null;
    protected $filter = null;
    protected $source = null;

    /**
     * @var RecordTransformer
     */
    protected $recordTransformer;

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
        if (!file_exists($source))
        {
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
        $table = new Table($this->source);

        $this->cache = array();
        while ($record = $table->nextRecord())
        {
            if ($record->isDeleted())
                continue;

            $data = $this->recordTransformer->transform($record);

            if ($data != null)
            {
                $this->cache[] = $data;
                yield $data;
            }
        }
    }

    /**
     * Get the instance as an array.
     *
     * @throws \Exception
     * @return array
     */
    public function toArray()
    {
        if (is_null($this->cache))
        {
            throw new \Exception("cache is null, run parse() first!");
        }

        return $this->cache;
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
}