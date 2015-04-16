<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class AggregationCursor implements \Iterator
{
    protected $collection;
    private $_pipelines = [];
    
    private $_position = 0;
    private $_cursor;
    
    public function __construct($collection)
    {
        $this->collection = $collection;
    }
    
    public function pipeline($operator, $condition)
    {
        if (!empty($condition)) {
            $this->_pipelines[] = [$operator => $condition];
        }
    }
    
    public function sort($sort)
    {
        if ($sort) {
            $this->_pipelines[] = ['$sort' => $sort];
        }
    }
    
    public function limit($limit)
    {
        if ($limit) {
            $this->_pipelines[] = ['$limit' => $limit];
        }
    }
    
    public function skip($skip)
    {
        if ($skip) {
            $this->_pipelines[] = ['$skip' => $skip];
        }
    }
    
    public function rewind() {
        if ($this->_cursor === null) {
            $this->buildCursor();
        }
        $this->_position = 0;
    }

    public function current() {
        return $this->_cursor[$this->_position];
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        ++$this->_position;
    }

    public function valid() {
        if ($this->_cursor === null) {
            $this->buildCursor();
        }
        return isset($this->_cursor[$this->_position]);
    }

    public function hasNext()
    {
        return $this->valid();
    }

    public function getNext()
    {
        return $this->current();
    }

    public function count()
    {
		return count($this->_cursor);
    }

    public function info()
    {
        return [
        	'count' => $this->count()
        ];
    }
    
    private function buildCursor()
    {
        $collection = $this->collection;
        $prefix = $collection->prefix;
        if ($prefix) {
            array_unshift($this->_pipelines, ['$unwind' => '$'.$prefix]);
        }
        
        //TODO: here we can add list of fields to projection
        // as a select() method
        $project = ['_id' => 0, $prefix => '$'.$prefix];
        
        
        $this->_pipelines[] = ['$project' => $project];
        $rows = $collection->aggregate($this->_pipelines);
        foreach ($rows as $row) {
            $this->_cursor[] = $row[$prefix];
        }
    }
}