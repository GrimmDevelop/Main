<?php

namespace Grimm\Converter;

use Grimm\Contract\Converter;
use Grimm\Contract\RecordTransformer;
use XBase\Table;

class Letter implements Converter {

    protected $cache = null;
    protected $filter;
    protected $source;

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
    }

    public function setFilter(array $filter)
    {
        $this->filter = $filter;
    }

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

    public function toArray()
    {
        if (is_null($this->cache))
        {
            throw new \Exception("cache is null, run parse() first!");
        }

        return $this->cache;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
