<?php
namespace Core\DB;

use Core\Support\Exceptions\DbException;

/**
 * DB Connection class
 * 
 * @package     Core\DB
 * @author      GEE
 */
class Connection
{
    /**
     * @var Connection
     */
    private static $instance;

    /**
     * List of DB connections
     * @var array<PDO>
     */
    private $dbs            = [];

    /**
     * PDO statement
     * @var PDOStatement
     */
    private $sth            = [];

    /**
     * List of DB parameters
     * @var array<array>
     */
    private $configs        = [];

    /**
     * Default connection name
     * @var string
     */
    private $default        = 'default';
    /**
     * Connection name
     * @var string
     */
    private $name           = 'default';

    /**
     * Query History
     * Populates only if DEBUG MODE is ON
     * @var array
     */
    public static $history  = [];


    /**
     * Constructor
     */
    private function __construct()
    {
        $params = \Core\Register::config('database');
        $this->register($params);
    }
    
    
    /**
     * Create instance
     * @return Connection
     */
    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Return Query history
     * @return array
     */
    public function history()
    {
        return self::$history;
    }


    /**
     * Register connection configuration
     * @param array $params
     */
    public function register(array $params)
    {
        $params += [
            'name'      => $this->default,
            'type'      => 'write',
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'username'  => '',
            'password'  => '',
            'dbname'    => '',
            'charset'   => 'utf8',
            // 'charset'   => 'utf8mb4',
        ];

        $this->configs[ $params['name'] ] = $params;
        $this->dbs[ $params['name'] ] = null;
    }


    /**
     * Create DB connection
     * 
     * @link https://phpdelusions.net/pdo_examples/connect_to_mysql
     * 
     * @param bool   $refresh
     * 
     * @return \PDO
     * @throws \Exception
     */
    public function connect(bool $refresh = false)
    {
        // return connection
        if (!$refresh && !empty($this->dbs[$this->name])) {
            return $this->dbs[$this->name];
        }

        // check configration
        if (empty($this->configs[$this->name])) {
            throw new DbException("Connection [{$this->name}] does not exists.");
        }
        
        /*
         * Establish connection
         * https://www.php.net/manual/en/pdo.setattribute.php
         */
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_CASE               => \PDO::CASE_NATURAL,
            // \PDO::ATTR_CASE               => \PDO::CASE_UPPER,
            // \PDO::ATTR_EMULATE_PREPARES   => false,// mysql only
        ];

        $params = $this->configs[$this->name];

        /*
         * Build DNS
         * https://www.php.net/manual/en/function.sqlsrv-connect.php
         * https://www.php.net/manual/en/ref.pdo-sqlsrv.connection.php
         */
        if ($params['driver'] == 'mssql') {
            $dsn = "sqlsrv:Server={$params['host']},{$params['port']};Database={$params['dbname']}";
        } else {
            $dsn = "mysql:host={$params['host']};port={$params['port']};dbname={$params['dbname']};charset={$params['charset']}";
        }
        
        $this->dbs[$this->name] = new \PDO($dsn, $params['username'], $params['password'], $options);
        // try {
        //      $pdo = new \PDO($dsn, $params['username'], $params['password'], $options);
        // } catch (\PDOException $e) {
        //      throw new \PDOException($e->getMessage(), $e->getCode());
        // }
        
        return $this->dbs[$this->name];
    }


    /**
     * Set DB connection name
     * 
     * @param string $name  Connection name
     * @return Connection
     */
    public function db(string $name = null)
    {
        $this->name = $name ?: $this->default;
        return $this;
    }


    /**
     * Executes query
     * 
     * @link https://www.php.net/manual/en/pdostatement.execute.php
     * 
     * @param string $query     SQL query string
     * @param array  $values    SQL query values
     * @return bool
     */
    public function exec(string $query, array $values = [])
    {
        $query = trim($query);

        // check if allow to modify data
        if ($this->configs[$this->name]['type'] == 'read') {
            $stat = strripos($query, 'INSERT ') === 0 || strripos($query, 'UPDATE ') === 0;
            if ($stat) {
                throw new DbException("Connection [{$this->name}] does not allow to modify data.");
            }
        }
        
        // execute
        \Core\Support\Debug::start(__METHOD__);

        try {
            $this->sth = $this->connect()->prepare($query);
            $state = $this->sth->execute($values);
        } catch (\Exception $e) {
            throw new DbException($e->getMessage(), $e->getCode(), $e);
        }

        $time  = \Core\Support\Debug::stop(__METHOD__);
        
        // save history
        if (\Core\Register::get('debug')) {
            self::$history[] = [
                'query'  => $query,
                'values' => $values,
                'time'   => $time,
                'track'  => debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 2),
            ];
        }

        return $state;
    }


    /**
     * Fetches the next row from a result set
     * 
     * @link https://www.php.net/manual/en/pdostatement.fetch.php
     * @return mixed
     */
    public function fetch()
    {
        if (!$this->sth) {
            return null;
        }
        // $this->sth->setFetchMode(\PDO::FETCH_CLASS, \App\Models\City::class);
        return $this->sth->fetch(\PDO::FETCH_ASSOC) ?: null;
    }


    /**
     * Returns an array containing all of the result set rows
     * 
     * @link https://www.php.net/manual/en/pdostatement.fetchall.php
     * @return array
     */
    public function fetchAll()
    {
        if (!$this->sth) {
            return null;
        }
        return $this->sth->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Initiates a transaction
     * 
     * @link https://www.php.net/manual/en/pdo.begintransaction.php
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->connect()->beginTransaction();
    }


    /**
     * Commits a transaction
     * 
     * @link https://www.php.net/manual/en/pdo.commit.php
     * @return bool
     */
    public function commit()
    {
        return $this->connect()->commit();
    }


    /**
     * Rolls back a transaction
     * 
     * @link https://www.php.net/manual/en/pdo.rollback.php
     * @return bool
     */
    public function rollback()
    {
        return $this->connect()->rollBack();
    }
}
