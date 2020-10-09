<?php
namespace Core\DB;


/**
 * @link https://www.w3schools.com/sql/sql_exists.asp
 * @link https://www.mysqltutorial.org/mysql-exists/
 * @link https://www.tutorialrepublic.com/sql-tutorial/sql-top-clause.php
 */
class QueryBuilder
{
    protected static $objCount = 0;
    protected $objId = 0;
    
    /**
     * Table name
     * @var string
     */
    protected $table    = '';
    protected $fields   = [];
    protected $wheres   = [];
    protected $joins    = [];
    protected $groupBy  = '';
    protected $orderBy  = '';
    protected $limit    = 0;
    protected $offset   = 0;
    protected $having   = '';
    /**
     * Union query
     * @var string
     */
    protected $union   = null;
    
    protected $query    = [];
    protected $values   = [];


    /**
     * Create object
     */
    public function __construct()
    {
        $this->objId = 'o' . (++self::$objCount);
    }

    /**
     * Set table name
     * 
     * @param string $name  Table name
     * @return QueryBuilder
     */
    public function table($name)
    {
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
     * @return Builder
     */
    public function join(string $type, string $table, string $condition)
    {
        $this->joins[] = ['type' => strtoupper($type), 'table' => $table, 'condition' => $condition];
        return $this;
    }


    /**
     * Set query limit
     * 
     * @param int $limit  Query limit
     * @return QueryBuilder
     */
    public function top(int $limit)
    {
        return $this->limit($limit)->offset(0);
    }


    /**
     * Set query limit
     * 
     * @param int $limit  Query limit
     * @return QueryBuilder
     */
    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }


    /**
     * Set query offset
     * 
     * @param int $limit  Query offset
     * @return QueryBuilder
     */
    public function offset(int $offset)
    {
        $this->offset = $offset;
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
    public function where($field, $operator, $value)
    {
        return $this->buildCondition('AND', $field, $operator, $value);
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
    public function orWhere($field, $operator, $value)
    {
        return $this->buildCondition('OR', $field, $operator, $value);
    }
    

    /**
     * SQL WHERE-IN clause
     * 
     * @param string $field
     * @param mixed  $value
     * 
     * @return QueryBuilder
     */
    public function whereIn($field, $value)
    {
        return $this->buildCondition('AND', $field, 'IN', $value);
    }


    /**
     * SQL OR-WHERE-IN clause
     * 
     * @param string $field
     * @param mixed  $value
     * @param bool   $not
     * 
     * @return QueryBuilder
     */
    public function orWhereIn($field, $value)
    {
        return $this->buildCondition('OR', $field, 'IN', $value);
    }


    /**
     * SQL WHERE-NOT-IN clause
     * 
     * @param string $field
     * @param mixed  $value
     * 
     * @return QueryBuilder
     */
    public function whereNotIn($field, $value)
    {
        return $this->buildCondition('AND', $field, 'NOT IN', $value);
    }


    /**
     * SQL OR-WHERE-NOT-IN clause
     * 
     * @param string $field
     * @param mixed  $value
     * 
     * @return QueryBuilder
     */
    public function orWhereNotIn($field, $value)
    {
        return $this->buildCondition('OR', $field, 'NOT IN', $value);
    }


    /**
     * Set: LIKE
     * 
     * @param string $column    Column name
     * @param string $value     Column value
     * @param string $type      LIKE type [both, left, right]
     * 
     * @return Builder
     */
    public function like(string $column, string $value, string $type = 'both')
    {
        return $this->buildCondition('AND', $column, 'LIKE', $value, $type);
    }


    /**
     * Set: OR LIKE
     * 
     * @param string $column    Column name
     * @param string $value     Column value
     * @param string $type      LIKE type [both, left, right]
     * 
     * @return Builder
     */
    public function orLike(string $column, string $value, string $type = 'both')
    {
        return $this->buildCondition('OR', $column, 'LIKE', $value, $type);
    }


    /**
     * Set: NOT LIKE
     * 
     * @param string $column    Column name
     * @param string $value     Column value
     * @param string $type      LIKE type [both, left, right]
     * 
     * @return Builder
     */
    public function notLike(string $column, string $value, string $type = 'both')
    {
        return $this->buildCondition('AND', $column, 'NOT LIKE', $value, $type);
    }


    /**
     * Set: OR NOT LIKE
     * 
     * @param string $column    Column name
     * @param string $value     Column value
     * @param string $type      LIKE type [both, left, right]
     * 
     * @return Builder
     */
    public function orNotLike(string $column, string $value, string $type = 'both')
    {
        return $this->buildCondition('OR', $column, 'NOT LIKE', $value, $type);
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
     * 
     */
    public function get()
    {
        $query = $this->buildSelect();
        return self::exec($query, $this->values);
    }


    /**
     * 
     */
    public function insert(array $data)
    {
        $query = $this->buildInsert($data);
        return self::exec($query, $this->values);
    }


    /**
     * 
     */
    public function update(array $data)
    {
        $query = $this->buildUpdate($data);
        return self::exec($query, $this->values);
    }


    /**
     * 
     */
    protected static function exec($query, array $value = [])
    {
        return $query;
    }


    /**
     * Build select query
     * 
     * @return string
     */
    public function buildSelect()
    {
        $this->query = [];
        $this->query[] = "SELECT";
        $this->query[] = $this->fields ? join(",", $this->fields) : '*';
        $this->query[] = "FROM";
        $this->query[] = $this->table;

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
     * Build insert query
     * 
     * @return string
     */
    public function buildInsert(array $data)
    {
        $this->query = [];
        $this->query[] = "INSERT INTO";
        $this->query[] = $this->table;
        $this->query[] = "(...)";
        $this->query[] = "VALUES";
        $this->query[] = "(...)";

        return join("\n", $this->query);
    }


    /**
     * Build update query
     * 
     * @return string
     */
    public function buildUpdate(array $data)
    {
        $this->query = [];
        $this->query[] = "UPDATE";
        $this->query[] = $this->table;
        $this->query[] = "SET";

        $set = [];
        foreach ($data as $name => $value) {
            $set[] = "{$name} = " . $this->buildValue($value);
        }
        $this->query[] = join(",", $set);

        $this->buildWhereClause();
        
        return join("\n", $this->query);
    }



    /**
     * Build WHERE Condition
     * 
     * @param string $logic
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * @param string $type
     * 
     * @return QueryBuilder
     */
    protected function buildCondition(string $logic, $field, $operator, $value, $type = 'both')
    {
        $logic    = strtoupper($logic);
        $operator = strtoupper($operator);

        switch ($operator) {
            case 'IN':
            case 'NOT IN':
                $value     = $this->buildValue($value);
                $condition = "{$field} {$operator} ({$value})";
                break;
            case 'LIKE':
            case 'NOT LIKE':
                $value  = (in_array($type, ['both', 'left'])  ? '%' : '') . $value;
                $value  = $value . (in_array($type, ['both', 'right'])  ? '%' : '');
                
                $value     = $this->buildValue($value);
                $condition = "{$field} {$operator} {$value}";
                break;
            default:
                if (is_null($value)) {
                    $operator = 'IS';
                    $value = 'NULL';
                } else {
                    $value = $this->buildValue($value);
                }
                
                $condition = "{$field} {$operator} {$value}";
        }

        $isEmpty = empty($this->wheres);
        $this->wheres[] = ($isEmpty ? '' : $logic . ' ') . $condition;

        return $this;
    }


    /**
     * Build Join Clause
     */
    protected function buildJoinClause()
    {
        foreach ($this->joins as $join) {
            $this->query[] = $join['type'] . ' JOIN ' . $join['table'] . ' ON (' . $join['condition'] . ')';
        }
    }


    /**
     * Build Value
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildWhereClause()
    {
        if ($this->wheres) {
            $this->query[] = "WHERE " . join(" ", $this->wheres);
        }
    }


    /**
     * Build Value
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildLimitClause()
    {
        if ($this->limit > 0) {
            $this->query[] = "LIMIT {$this->limit} OFFSET {$this->offset}";
        }
    }


    /**
     * Build values
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function buildValue($value)
    {
        if (is_null($value)) {
            return null;
        } else if (is_array($value)) {
            $values = [];
            foreach ($value as $val) {
                $values[] = $this->processValue($val);
            }
            $value = join(',', $values);
        } else {
            $value = $this->processValue($value);
        }
        
        return $value;
    }


    /**
     * Process value
     * 
     * @param mixed $value
     * @return mixed
     */
    protected function processValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        return "'{$value}'";

        // $count = count($this->values);
        // $tmp = ":{$this->objId}v{$count}";
        // $this->values[$tmp] = $value;
        // return $tmp;
    }
}
