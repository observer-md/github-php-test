<?php
namespace vendor\core;

use vendor\libraries\DateTime;

/**
 * DB Models class
 * 
 * @author Eugene <observer_md@yahoo.com>
 */
abstract class Model
{
    /**
     * Query limit
     * @var int
     */
    protected $limit = 50;

    /**
     * DB object
     * @var DB
     */
    private $db;

    /**
     * Table name
     * @var string
     */
    protected $table = '';

    /**
     * List of fields
     * @var array
     */
    protected $fields = [];

    /**
     * 
     */
    public function __construct()
    {
        $this->db = DB::init();
    }

    /**
     * 
     */
    public static function init()
    {
        return new static();
    }

    /**
     * Return DB
     */
    public function getDb()
    {
        return $this->db->getConn();
    }


    /**
     * Find record
     * 
     * @return array
     */
    public function find(array $params)
    {
        $where  = [];
        $values = [];

        // set ID
        if (array_key_exists('id', $params)) {
            $where[] = 'id = :id';
            $values['id'] = $params['id'];
        }

        // set TOKEN
        if (array_key_exists('token', $params)) {
            $where[] = 'token = :token';
            $values['token'] = $params['token'];
        }

        // $query = 'SELECT * FROM ' . $this->table . ' WHERE token = :token';
        $query = 'SELECT * FROM ' . $this->table;

        if ($where) {
            $query .= ' WHERE ' . join(' AND ', $where);
        }
        
        $stmt = $this->getDb()->prepare($query);

        foreach ($values as $name => $value) {
            $stmt->bindValue(":{$name}", $value, self::getBindValueType($value));
        }
        
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }


    /**
     * Find all records
     * 
     * @return array
     */
    public function findAll(array $params = [])
    {
        $params += [
            'limit'  => $this->limit,
            'offset' => 0,
        ];

        $query = 'SELECT * FROM ' . $this->table . ' LIMIT :limit OFFSET :offset';

        $stmt = $this->getDb()->prepare($query);

        foreach ($params as $name => $value) {
            $stmt->bindValue(":{$name}", $value, self::getBindValueType($value));
        }

        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    /**
     * Create record
     * 
     * @param array $params     Record data
     * 
     * @return bool
     * @throws \Exception
     */
    public function create(array $params)
    {
        unset($params['id']);
        $params = $this->validate($params);
        
        // set created date
        if (isset($this->fields['created_date'])) {
            $params['created_date'] = DateTime::formatDateTime();
        }

        // set updated date
        if (isset($this->fields['updated_date'])) {
            $params['updated_date'] = DateTime::formatDateTime();
        }

        // get fields
        $fields = [];
        $values = [];

        $params = $this->validate($params);
        foreach ($params as $field => $value) {
            $name = ':' . $field;
            $fields[] = $field . ' = ' . $name;
            $values[$name] = $value;
        }

        $fields = join(',', $fields);

        // build query
        $query = 'INSERT INTO ' . $this->table . ' SET ' . $fields;
        $stmt  = $this->getDb()->prepare($query);

        return $stmt->execute($values);
    }

    /**
     * Update record
     * 
     * @param int   $id         Record ID
     * @param array $params     Record data
     * 
     * @return bool
     * @throws \Exception
     */
    public function update($id, array $params)
    {
        // get fields
        $fields = [];
        $values = [':id' => $id];

        $params = $this->validate($params);

        // set updated date
        if (isset($this->fields['updated_date'])) {
            $params['updated_date'] = DateTime::formatDateTime();
        }

        foreach ($params as $field => $value) {
            $name = ':' . $field;
            $fields[] = $field . ' = ' . $name;
            $values[$name] = $value;
        }

        $fields = join(',', $fields);

        // build query
        $query = 'UPDATE ' . $this->table . ' SET ' . $fields . ' WHERE id = :id';
        
        $stmt  = $this->getDb()->prepare($query);

        return $stmt->execute($values);
    }

    /**
     * Delete record
     * 
     * @param int $id   Record ID
     * 
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        $id = (int) $id;

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $id;
        $stmt  = $this->getDb()->prepare($query);
        return $stmt->execute();
    }


    /**
     * Validate data
     * 
     * @param array Record data
     * @return array
     */
    protected function validate(array $data)
    {   
        $fieldsData = [];
        foreach ($this->fields as $name => $conf) {

            // check required field
            if (isset($conf['required'])) {
                if (empty($data[$name])) {
                    throw new \Exception("Field \"{$name}\" cant be empty.");
                }
            }

            if (isset($data[$name])) {
                $fieldsData[$name] = $data[$name];
            }
        }

        return $fieldsData;
    }

    /**
     * Return bind value type
     * 
     * @param mixed $value
     * @return int|string
     */
    protected static function getBindValueType($value)
    {
       return is_integer($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
    }
}