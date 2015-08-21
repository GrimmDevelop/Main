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
     * @var \XBase\Table
     */
    protected $table;

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

        $this->table = new Table($this->source);
    }

    /**
     * Sets filter for returned data
     * @param array $filter
     */
    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }

    public function skipTo($firstIndex)
    {
        if ($this->table !== null && $firstIndex > 0) {
            $this->table->moveTo($firstIndex - 1);
        }
    }

    public function totalEntries()
    {
        return ($this->table !== null) ? $this->table->recordCount : null;
    }

    /**
     * Parses given source file
     * @return yield array
     */
    public function parse()
    {
        //$table = new Table($this->source);
        if ($this->table === null) {
            throw new Exception('Table not loaded');
        }

        $this->cache = array();
        while ($record = $this->table->nextRecord())
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

    /**
     * @return int
     */
    public function total()
    {
        return $this->table->getRecordCount();
    }
}