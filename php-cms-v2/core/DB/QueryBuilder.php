<?php
namespace Core\DB;


/**
 * @link https://www.w3schools.com/sql/sql_exists.asp
 * @link https://www.mysqltutorial.org/mysql-exists/
 * @link https://www.tutorialrepublic.com/sql-tutorial/sql-top-clause.php
 * 
 * @package     Core\DB
 * @author      GEE
 */
class QueryBuilder
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
    protected $objId        = null;
    
    /**
     * Table name
     * @var string
     */
    protected $table        = '';
    protected $fields       = [];
    protected $wheres       = [];
    protected $joins        = [];
    protected $groupBy      = '';
    protected $orderBy      = '';
    protected $limit        = 0;
    protected $offset       = 0;
    protected $having       = '';
    /**
     * Union query
     * @var string
     */
    protected $union        = null;
    /**
     * WITH
     * @var array
     */
    protected $withs        = [];
    

    protected $groupWhere   = null;
    protected $query        = [];
    protected $values       = [];
    protected $valuesBind   = true;
    protected $connectName  = null;


    /**
     * Create object
     */
    public function __construct()
    {
        self::$objCount++;
        if (!$this->objId) {
            $this->objId = 'qb' . (self::$objCount);
        }
    }


    /**
     * Return new instance
     * @return 
     */
    public static function init()
    {
        return new self();
    }


    /**
     * Set DB connection
     * 
     * @param string $name  Connection name
     * @return Connection
     */
    public function setDb(string $name = null)
    {
        $this->connectName = $name;
        return $this;
    }


    /**
     * Return DB connection
     */
    public function db()
    {
        return Connection::init()->db($this->connectName);
    }


    /**
     * Set table name
     * 
     * @param string $name  Table name
     * @return QueryBuilder
     */
    public function table($name)
    {
        $name = trim($name);
        // set obj-id by table alias
        if ($pos = strpos($name, ' ')) {
            if ($id = trim(substr($name, $pos))) {
                $this->objId = $id;
            }
        }
        
        $this->table = $name;
        return $this;
    }


    /**
     * Set select fields
     * 
     * @param string $name  Table name
     * @return QueryBuilder
     */
    public function fields($fields)
    {
        $this->fields[] = $fields;
        return $this;
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
    public function join(string $type, string $table, string $condition)
    {
        $this->joins[] = ['type' => strtoupper($type), 'table' => $table, 'condition' => $condition];
        return $this;
    }


    /**
     * Set: TOP LIMIT
     * 
     * @param int $limit  Query limit
     * @return QueryBuilder
     */
    // public function top(int $limit)
    // {
    //     return $this->limit($limit)->offset(0);
    // }


    /**
     * Set: LIMIT
     * 
     * @param int $value  Query limit
     * @return QueryBuilder
     */
    public function limit(int $value)
    {
        $this->limit = $value;
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
        $this->offset = $value;
        return $this;
    }


    /**
     * Set GROUP-BY
     * 
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function groupBy(string $groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }


    /**
     * Set ORDER-BY
     * 
     * @param string $orderBy
     * @return QueryBuilder
     */
    public function orderBy(string $orderBy)
    {
        $this->orderBy = $orderBy;
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
        $this->wheres[] = [
            'logic'      => 'AND',
            'field'      => $field,
            'operator'   => $operator,
            'value'      => $value,
            'groupBegin' => $this->groupWhere,
            'groupEnd'   => null,
        ];
        $this->groupWhere = null;

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
        $this->wheres[] = [
            'logic'      => 'OR',
            'field'      => $field,
            'operator'   => $operator,
            'value'      => $value,
            'groupBegin' => $this->groupWhere,
            'groupEnd'   => null,
        ];
        $this->groupWhere = null;

        return $this;
    }
    

    /**
     * Set: Begin WHERE group
     * 
     * @return QueryBuilder
     */
    public function groupBegin()
    {
        $this->groupWhere .= '(';
        return $this;
    }


    /**
     * Set: End WHERE group
     * 
     * @return QueryBuilder
     */
    public function groupEnd()
    {
        // add group end to the last where
        $pos = count($this->wheres) - 1;
        $this->wheres[$pos]['groupEnd'] .= ')';
        
        return $this;
    }


    /**
     * Set: HAVING
     * 
     * @param string   $having
     * @return QueryBuilder
     */
    public function having($having)
    {
        $this->having = $having;
        return $this;
    }


    /**
     * Set: [NOT] EXISTS
     * 
     * @link https://www.mysqltutorial.org/mysql-exists/
     * 
     * @param QueryBuilder|string   $builder    EXISTS subquery
     * @param bool                  $not        NOT EXISTS subquery
     * 
     * @return QueryBuilder
     */
    public function exists($builder, bool $not = false)
    {
        $query = is_object($builder) ? $builder->buildSelect() : $builder;
        $this->wheres[] = ($this->wheres ? "AND " : "")
            . ($not ? "NOT " : "") . "EXISTS ({$query})";
        return $this;
    }


    /**
     * Set: UNION [ALL]
     * 
     * @link https://www.mysqltutorial.org/sql-union-mysql.aspx
     * 
     * @param QueryBuilder|string   $builder    UNION query
     * @param bool                  $all        ALL UNION query
     * 
     * @return QueryBuilder
     */
    public function union($builder, bool $all = false)
    {
        $query = is_object($builder) ? $builder->buildSelect() : $builder;
        $this->union = "UNION" . ($all ? " ALL " : " ") . $query;
        return $this;
    }
    

    /**
     * Set: WITH
     * 
     * @param string        $name
     * @param QueryBuilder  $builder    UNION query
     * 
     * @return QueryBuilder
     */
    public function with($name, $builder)
    {
        $this->withs[] =  $name . ' (' . $builder->buildSelect() . ')';
        return $this;
    }


    /**
     * Execute SELECT
     * 
     * @param bool $all     Return all
     * @return array
     */
    public function select($all = true)
    {
        $query = $this->buildSelect();
        $this->db()->exec($query, $this->values);
        $this->reset();

        return $all ? $this->db()->fetchAll() : $this->db()->fetch();
    }


    /**
     * Execute INSERT
     * 
     * @param array $data
     * @return
     */
    public function insert(array $data)
    {
        $query = $this->buildInsert($data);
        $state = $this->db()->exec($query, $this->values);
        $this->reset();

        return $state;
    }


    /**
     * Execute UPDATE
     * 
     * @param array $data
     * @return
     */
    public function update(array $data)
    {
        $query = $this->buildUpdate($data);
        $state = $this->db()->exec($query, $this->values);
        $this->reset();

        return $state;
    }


    /**
     * Execute DELETE
     * 
     * @return bool
     */
    public function delete()
    {
        $query = $this->buildDelete();
        $state = $this->db()->exec($query, $this->values);
        $this->reset();

        return $state;
    }


    /**
     * Build SELECT query
     * 
     * @return string
     */
    public function buildSelect()
    {
        $this->query  = [];
        $this->values = [];
        
        $this->buildWithClause();

        $this->query[] = "SELECT";
        // $this->query[] = "SELECT" . ($this->top > 0 ? " TOP {$this->top}" : "");

        $this->query[] = $this->fields ? join("\n,", $this->fields) : '*';
        $this->query[] = "FROM {$this->table}";
        
        
        $this->buildJoinClause();
        $this->buildWhereClause();

        // add union
        if ($this->union) {
            $this->query[] = $this->union;
        }

        // add groupBy
        if ($this->groupBy) {
            $this->query[] = "GROUP BY {$this->groupBy}";
        }

        // add orderBy
        if ($this->orderBy) {
            $this->query[] = "ORDER BY {$this->orderBy}";
        }

        $this->buildLimitClause();

        return join("\n", $this->query);
    }


    /**
     * Build INSERT query
     * 
     * @return string
     */
    public function buildInsert(array $data)
    {
        $this->query  = [];
        $this->values = [];

        $isMultipleInsert = isset($data[0]) && is_array($data[0]);

        // fields
        $fields = array_keys(($isMultipleInsert ? $data[0] : $data));
        $fields = join(", ", $fields);

        // values
        $values = [];
        if ($isMultipleInsert) {
            foreach ($data as $row) {
                $values[] = '(' . $this->buildValue($row) . ')';
            }
        } else {
            $values[] = '(' . $this->buildValue($data) . ')';
        }
        
        $this->query[] = "INSERT INTO {$this->table}";
        $this->query[] = "({$fields})";

        // $this->query[] = "OUTPUT INSERTED.*";

        $this->query[] = "VALUES";
        $this->query[] = join(",\n", $values);
        
        return join("\n", $this->query);
    }


    /**
     * Build UPDATE query
     * 
     * @return string
     */
    public function buildUpdate(array $data)
    {
        // $this->valuesBind = true;
        $this->query  = [];
        $this->values = [];

        $set = [];
        foreach ($data as $name => $value) {
            $set[] = "{$name} = " . $this->buildValue($value);
        }

        $this->query[] = "UPDATE {$this->table}";
        $this->query[] = "SET";

        $this->query[] = join(", ", $set);

        // $this->query[] = "OUTPUT INSERTED.*";

        $this->buildWhereClause();
        
        
        return join("\n", $this->query);
    }



    /**
     * Build DELETE query
     * 
     * @return string
     */
    public function buildDelete()
    {
        $this->query  = [];
        $this->values = [];

        // $this->query[] = "DELETE" . ($this->top > 0 ? " TOP {$this->top}" : "");
        $this->query[] = "DELETE {$this->objId} FROM {$this->table}";
        
        // $this->query[] = "OUTPUT DELETED.*";

        $this->buildWhereClause();
        
        return join("\n", $this->query);
    }


    /**
     * Reset builder data
     */
    protected function reset()
    {
        $this->table        = '';
        $this->fields       = [];
        $this->wheres       = [];
        $this->joins        = [];
        $this->groupBy      = '';
        $this->orderBy      = '';
        $this->limit        = 0;
        $this->offset       = 0;
        $this->having       = '';
        $this->union        = null;
        $this->withs        = [];
        

        $this->groupWhere   = null;
        $this->query        = [];
        $this->values       = [];
    }


    /**
     * Build condition
     * 
     * @param array $data
     * @param array $list
     * @return QueryBuilder
     */
    protected function buildCondition(array $data, array &$list)
    {
        $data += [
            'logic'      => 'AND',
            'field'      => null,
            'operator'   => null,
            'value'      => null,
            'groupBegin' => null,
            'groupEnd'   => null,
        ];

        $logic      = empty($data['logic']) || empty($list) ? null : strtoupper($data['logic']) . " ";
        $operator   = empty($data['operator'])              ? null : strtoupper($data['operator']);
        $field      = empty($data['field'])                 ? null : $data['field'];
        $value      = !isset($data['value'])                ? null : $data['value'];
        $groupBegin = empty($data['groupBegin'])            ? null : $data['groupBegin'];
        $groupEnd   = empty($data['groupEnd'])              ? null : $data['groupEnd'];
        
        switch ($operator) {
            case 'IS NULL':
                $operator = 'IS';
                $value    = 'NULL';
                break;
            case 'IS NOT NULL':
                $operator = 'IS NOT';
                $value    = 'NULL';
                break;
            case 'IN':
            case 'NOT IN':
                $value = '(' . $this->buildValue($value) . ')';
                break;
            default:
                $value = $this->buildValue($value);
        }

        return "{$groupBegin}{$logic}{$field} {$operator} {$value}{$groupEnd}";
    }


    /**
     * Build WITH Clause
     */
    protected function buildWithClause()
    {
        if ($this->withs) {
            $this->query[] = "WITH " . join("\n,", $this->withs);
        }
    }


    /**
     * Build WHERE Clause
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildWhereClause()
    {
        if ($this->wheres) {
            $list = [];
            foreach ($this->wheres as $where) {
                $list[] = $this->buildCondition($where, $list);
            }
            $this->query[] = "WHERE " . join("\n", $list);
        }
    }


    /**
     * Build JOIN Clause
     */
    protected function buildJoinClause()
    {
        foreach ($this->joins as $join) {
            $this->query[] = $join['type'] . ' JOIN ' . $join['table'] . ' ON (' . $join['condition'] . ')';
        }
    }


    /**
     * Build LIMIT Clause
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildLimitClause()
    {
        if ($this->limit > 0) {
            $this->query[] = "LIMIT {$this->limit} OFFSET {$this->offset}";
            // $this->query[] = "OFFSET {$this->offset} ROWS FETCH NEXT {$this->limit} ROWS ONLY";
        }
    }


    /**
     * Build value
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildValue($value)
    {
        if (!is_array($value)) {
            return $this->processValue($value);
        }

        $values = array_map([$this, 'processValue'], $value);
        return join(',', $values);
    }


    /**
     * Process and return value
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function processValue($value)
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_numeric($value)) {
            return $value;
        }

        if (!is_string($value)) {
            $value = trim((string) $value);
        }

        if (!$this->valuesBind) {
            return "'" . addslashes($value) . "'";
            // return "'" . htmlspecialchars($value, ENT_QUOTES|ENT_HTML5) . "'";
        }
        
        $count = count($this->values);
        $tmp   = ":{$this->objId}v{$count}";
        $this->values[$tmp] = $value;
        return $tmp;
    }
}
