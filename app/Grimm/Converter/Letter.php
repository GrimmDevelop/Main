<?php

namespace Grimm\Converter;

use Exception;
use Grimm\Contract\Converter;
use Grimm\Contract\RecordTransformer;
use XBase\Table;

class Letter implements Converter {

    protected $cache = null;
    protected $filter;
    protected $source;

    /**
     * @var \XBase\Table
     */
    protected $table;

    /**
     * @var RecordTransformer
     */
    protected $recordTransformer;

    /**
     * @param RecordTransformer $recordTransformer
     */
    public function __construct(RecordTransformer $recordTransformer)
    {
        $this->recordTransformer = $recordTransformer;
    }

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

    public function toArray()
    {
        if (is_null($this->cache))
        {
            throw new Exception("cache is null, run parse() first!");
        }

        return $this->cache;
    }

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
