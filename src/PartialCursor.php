<?php
namespace mongoex;

use yii\base\Object;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class PartialCursor extends Object implements \Iterator
{
    public $resultKey = 'result';
    public $parentIdKey = 'parentId';
    public $data; 
    
    private $_position = -1;
    private $_cursor = [];
    
    public function init()
    {
        foreach ($this->data as $row) {
            $prepared = $row[$this->resultKey];
            $prepared[$this->parentIdKey] = isset($row[$this->parentIdKey]) ? (string)$row[$this->parentIdKey] : null;
            $this->_cursor[] = $prepared;
        }
    }

    public function rewind() {
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
        return isset($this->_cursor[$this->_position]);
    }
    
    public function hasNext()
    {
        $nextPosition = $this->_position + 1;
        return isset($this->_cursor[$nextPosition]);
    }
    
    public function getNext()
    {
        $this->next();
        if (!$this->valid()) {
            return null;
        }
        return $this->current();
    }
    
    public function info()
    {
        return ['count' => count($this->_cursor)];
    }
}