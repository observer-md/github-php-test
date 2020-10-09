<?php
namespace Core\Support;


/**
 * Collection class
 * 
 * @link https://www.php.net/manual/en/class.arrayaccess.php
 * @link https://www.php.net/manual/en/class.iterator.php
 * @link https://www.php.net/manual/en/class.countable
 * 
 * @package     Core\Support
 * @author      GEE
 */
class CollectionLite implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * Iterator position
     * @var int
     */
    protected $position = 0;
    /**
     * List of raw data
     * @var array<array>
     */
    protected $dataRaw = [];
    
    
    /**
     * Constructor
     * 
     * @param array  $raw       Raw data
     * @param object $model     Model uses to create object
     */
    public function __construct(array $raw, $model = null)
    {
        $this->position = 0;
        $this->dataRaw  = [];

        // set data
        if ($model) {
            foreach ($raw as $row) {
                $this->dataRaw[] = $model->createObject($row);
            }
        } else {
            $this->dataRaw = $raw;
        }
        
    }


    /**
     * Whether or not an offset exists
     * 
     * @param string $offset    An offset to check for
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->dataRaw[$offset]);
    }


    /**
     * Returns the value at specified offset
     * 
     * @param string $offset    The offset to retrieve
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function offsetGet($offset)
    {
        return $this->dataRaw[$offset] ?? null;
    }


    /**
     * Assigns a value to the specified offset
     * 
     * @param string $offset    The offset to assign the value to
     * @param mixed  $value     The value to set
     * @abstracting ArrayAccess
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->dataRaw[] = $value;
        } else {
            $this->dataRaw[$offset] = $value;
        }
    }


    /**
     * Unsets an offset
     * 
     * @param string $offset    The offset to unset
     * @abstracting ArrayAccess
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->dataRaw[$offset]);
            $this->dataRaw = array_slice($this->dataRaw, 0);
            $this->rewind();
        }
    }


    /**
     * Reset position
     * 
     * @abstracting Iterator
     */
    public function rewind()
    {
        $this->position = 0;
    }


    /**
     * Return current element
     * 
     * @return mixed
     * @abstracting Iterator
     */
    public function current()
    {
        return $this->offsetGet($this->position);
    }


    /**
     * Return current position
     * 
     * @return int
     * @abstracting Iterator
     */
    public function key()
    {
        return $this->position;
    }


    /**
     * Increase position
     * 
     * @abstracting Iterator
     */
    public function next()
    {
        ++$this->position;
    }


    /**
     * Validate position
     * 
     * @return bool
     * @abstracting Iterator
     */
    public function valid()
    {
        return $this->offsetExists($this->position);
    }


    /**
     * Count elements of an object
     * 
     * @return int
     * @abstracting Countable
     */
    public function count()
    {
        return count($this->dataRaw);
    }
}
