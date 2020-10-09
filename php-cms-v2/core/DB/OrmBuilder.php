<?php
namespace Core\DB;


/**
 * ORM Builder
 * 
 * @package     Core\DB
 * @author      GEE
 */
abstract class OrmBuilder
{
    /**
     * Class counter
     * @var int
     */
    protected static $objCount  = 0;
    /**
     * Object ID
     * @var string
     */
    protected $objId            = null;
    /**
     * Connection name
     * @var string
     */
    protected $connectName      = null;
    /**
     * Query Builder
     * @var QueryBuilder
     */
    protected $builder;


    /**
     * Create object
     */
    public function __construct()
    {
        self::$objCount++;
        if (!$this->objId) {
            $this->objId = 'm' . (self::$objCount);
        }
        $this->setBuilder();
    }


    /**
     * Set model builder
     * Method executes in constructor and builder params
     */
    protected function setBuilder()
    {
        if (!$this->builder) {
            $this->builder = $this->builder();
        }
    }


    /**
     * Return new instance
     * @return 
     */
    public static function init()
    {
        return new static();
    }


    /**
     * Return builder
     * @return QueryBuilder
     */
    public function builder()
    {
        return QueryBuilder::init()->setDb($this->connectName);
    }


    /**
     * Return DB connection
     * 
     * @param string $name  Connection name
     * @return Connection
     */
    public function db()
    {
        return $this->builder->db();
    }


    /**
     * Set: JOIN
     * 
     * @param string $type          [inner, left, right]
     * @param string $table
     * @param string $condition
     * 
     * @return QueryBuilder
     */
    // public function join(string $type, string $table, string $condition)
    // {
    //     $table = trim($table);
    //     $short = '';
    //     // set obj-id by table alias
    //     if ($pos = strpos($table, ' ')) {
    //         if ($id = trim(substr($table, $pos))) {
    //             $short = $id;

    //             $this->builder->fields("{$short}.*");
    //         }
    //     }


    //     $this->builder->join($type, $table, $condition);
    //     return $this;
    // }


    /**
     * Set: LIMIT
     * 
     * @param int $value  Query limit
     * @return QueryBuilder
     */
    public function limit(int $value)
    {
        $this->builder->limit($value);
        return $this;
    }


    /**
     * Set: OFFSET
     * 
     * @param int $value  Query offset
     * @return QueryBuilder
     */
    public function offset(int $value)
    {
        $this->builder->offset($value);
        return $this;
    }


    /**
     * Set GROUP-BY
     * 
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function groupBy(string $value)
    {
        $this->builder->groupBy($value);
        return $this;
    }


    /**
     * Set ORDER-BY
     * 
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function orderBy(string $value)
    {
        $this->builder->orderBy($value);
        return $this;
    }


    /**
     * SQL WHERE clause
     * 
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * 
     * @return QueryBuilder
     */
    public function where($field, $operator, $value = null)
    {
        $this->builder->where($this->fieldName($field), $operator, $value);
        return $this;
    }


    /**
     * SQL OR-WHERE clause
     * 
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * 
     * @return QueryBuilder
     */
    public function orWhere($field, $operator, $value = null)
    {
        $this->builder->orWhere($this->fieldName($field), $operator, $value);
        return $this;
    }


    /**
     * Build field name
     * 
     * @param string $field
     * @return string
     */
    protected function fieldName(string $field)
    {
        if (!strpos($field, '.')) {
            $field = "{$this->objId}.{$field}";
        }
        return $field;
    }
}
