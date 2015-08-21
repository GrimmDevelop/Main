<?php

namespace Grimm\Converter;

use Grimm\Contract\Converter;
use Grimm\Contract\RecordTransformer;
use League\Csv\Reader;

class Location implements Converter {

    protected $cache = null;
    protected $filter = null;
    protected $source = null;
    protected $limit = - 1;

    /**
     * @var \League\Csv\Reader
     */
    protected $csvReader = null;

    /**
     * @var RecordTransformer
     */
    protected $recordTransformer;
    protected $finished = false;

    public function __construct(RecordTransformer $recordTransformer)
    {
        $this->recordTransformer = $recordTransformer;
    }

    public function skipTo($destination)
    {
        if ($this->csvReader != null) {
            $this->csvReader->setOffset($destination);
        }
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        $this->csvReader->setLimit($limit);
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

        $this->csvReader = Reader::createFromPath($this->source);
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

        $handle = $this->csvReader;
        //$handle = Reader::createFromPath($this->source);
        $handle->setDelimiter("\t");

        $read = $handle->query();

        $i = 0;

        foreach ($read as $record) {
            if (count($record) < 19) {
                continue;
            }

            $data = $this->recordTransformer->transform($record);

            if ($data != null) {
                $this->cache[] = $data;
                yield $data;
            }
            $i ++;
        }

        // Have we read all lines?
        if ($this->limit > - 1 && $i + 1 < $this->limit) {
            $this->finished = true;
        }
    }

    public function total()
    {
        // TODO: optimize this one ...
        $f = fopen($this->source, 'rb');
        $lines = 0;

        while (!feof($f)) {
            $lines += substr_count(fread($f, 8192), "\n");
        }

        fclose($f);

        return $lines;
    }

    /**
     * @return boolean
     */
    public function isFinished()
    {
        return $this->finished;
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
        return json_encode($this->toArray(), $options);
    }
}