<?php
namespace Core;


/**
 * Base Model class
 * 
 * @package     Core
 * @author      GEE
 */
abstract class Model extends \Core\DB\OrmBuilder
{
    /**
     * Table name
     * @var string
     */
    protected $table        = null;
    /**
     * Table primary key
     * @var string|array
     */
    protected $primaryKey   = 'id';
    /**
     * Created date
     * @var string
     */
    protected $createdAt    = null;
    /**
     * Updated date
     * @var string
     */
    protected $updatedAt    = null;

    /**
     * Model attributes data
     * @var array
     */
    protected $attributes   = [];
    /**
     * Model original attributes data
     * @var array
     */
    protected $original     = [];
    /**
     * Errors list
     * @var array
     */
    protected $errors       = [];


    /**
     * Create object
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Return model attribute
     * 
     * @param string $name  Atribute name
     * @return mixed
     */
    public function __get($name)
    {
        if (!empty($this->rules()) && !isset($this->rules()[$name])) {
            throw new \Exception("Attribute [{$name}] does not exists.");
        }
        return $this->attributes[$name] ?? null;
    }


    /**
     * Set model attribute
     * 
     * @param string $name  Atribute name
     * @param mixed  $value Atribute value
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (!empty($this->rules()) && !isset($this->rules()[$name])) {
            throw new \Exception("Attribute [{$name}] does not exists.");
        }
        $this->attributes[$name] = $value;
    }


    /**
     * Check if model is new
     * @return bool
     */
    public function isNew()
    {
        return empty($this->original);
    }


    /**
     * Set model builder
     * Method executes in constructor and builder params
     */
    protected function setBuilder()
    {
        parent::setBuilder();

        $this->builder
            ->table("{$this->table} {$this->objId}")
            ->fields($this->fieldName("*"))
            ->limit(100);

        // order-by primary keys
        if ($this->primaryKey) {
            $fields = [];
            $keys   = is_array($this->primaryKey) ? $this->primaryKey : [$this->primaryKey];
            foreach ($keys as $key) {
                $fields[] = $this->fieldName($key) . " ASC";
            }
            $this->builder->orderBy( join(', ',  $fields) );
        }
    }


    /**
     * Return errors
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }


    /**
     * Create object and fill with raw data
     * 
     * @param Model
     */
    public static function createObject(array $data)
    {
        $obj = static::init();
        $obj->setData($data);
        return $obj;
    }


    /**
     * Set model attributes only if rules are set.
     * Return attibutes if $data is null.
     * 
     * @param array  $data      Attributes data
     * @return mixed
     */
    public final function attributes(array $data = null)
    {
        // get attributes
        if (is_null($data)) {
            return $this->attributes;
        }

        // set attrinutes
        foreach ($this->rules() as $rule) {
            $field = $rule['field'] ?? null;
            if (!$field) {
                continue;
            }
            $this->attributes[$field] = ($data[$field] ?? null);
        }
    }


    /**
     * Validation rules
     * @return array
     */
    protected function rules()
    {
        return [
            /*
            'field' => [
                'field'     => 'table-field-name',
                'label'     => 'Field Label',
                'type'      => 'field-type',
                'required'  => true,
                'nullable'  => true,
                'default'   => null,
                'min'       => 0,
                'max'       => 0,
                'allow'     => [],
            ],
            */
        ];
    }


    /**
     * Validate data
     * 
     * @return bool
     */
    public final function validate()
    {
        $this->errors = \Core\Support\Validation::validateData($this->rules(), $this->attributes);
        return empty($this->errors);
    }


    /**
     * Execute Find
     * 
     * @return
     */
    public function find()
    {
        $this->beforeFind();

        $data = $this->builder->limit(1)->select(false) ?: [];

        // return self::createObject($data);
        $this->setData($data);

        $this->setBuilder();
        $this->afterFind();

        return $this;
    }


    /**
     * Find all data
     * 
     * @return Collection
     */
    public function findAll()
    {
        $this->beforeFind();

        $data = $this->builder->select(true);

        // set first row
        $this->setData($data[0] ?? []);
        $this->setBuilder();

        // return model collection
        // return new \Core\Support\Collection($data, $this);
        return new \Core\Support\CollectionLite($data, $this);
    }


    /**
     * Save model data
     * 
     * @return
     */
    public function save()
    {
        // before save
        if (!$this->beforeSave()) {
            return false;
        }

        // validate before save
        if (!$this->validate()) {
            return false;
        }
        
        // save
        try {
            if ($this->isNew()) {
                $this->builder->insert($this->attributes());
            } else {
                $this->setPrimaryKeyWhere();
                $this->builder->update($this->attributes());
            }
        } catch (\Exception $e) {
            $this->errors['save'] = $e->getMessage();
        }

        $this->setBuilder();
        $this->afterSave();

        return empty($this->errors);
    }


    /**
     * Delete model data
     * 
     * @return
     */
    public function delete()
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        // delete
        try {
            $this->setPrimaryKeyWhere();
            $this->builder->delete();
        } catch (\Exception $e) {
            $this->errors['save'] = $e->getMessage();
        }

        $this->setBuilder();
        $this->afterDelete();
        return true;
    }


    /**
     * Runs Before Find
     * @return bool
     */
    protected function beforeFind()
    {
        return true;
    }
    /**
     * Runs Before Find
     * @return bool
     */
    protected function afterFind()
    {
        return true;
    }


    /**
     * Runs Before Find
     * @return bool
     */
    protected function beforeSave()
    {
        // set date fields
        $date = \Core\Support\DateTime::local();
        if ($this->createdAt && $this->isNew()) {
            $this->attributes[$this->createdAt] = $date;
        }
        if ($this->updatedAt) {
            $this->attributes[$this->updatedAt] = $date;
        }

        return true;
    }
    /**
     * Runs Before Find
     * @return bool
     */
    protected function afterSave()
    {
        return true;
    }


    /**
     * Runs Before Find
     * @return bool
     */
    protected function beforeDelete()
    {
        return true;
    }
    /**
     * Runs Before Find
     * @return bool
     */
    protected function afterDelete()
    {
        return true;
    }


    /**
     * Set Primary Key Where
     */
    protected function setPrimaryKeyWhere()
    {
        $keys = [];
        if ($this->primaryKey) {
            $keys = is_array($this->primaryKey) ? $this->primaryKey : [$this->primaryKey];
        } else if ($this->rules()) {
            $keys = array_keys($this->rules());
        } else {
            throw new \Exception("Model rules or primary-key can't be empty.");
        }

        // set where fields
        foreach ($keys as $key) {
            $this->where($key, '=', $this->{$key});
        }
    }


    /**
     * Set model data
     * 
     * @param array $data
     */
    protected function setData(array $data)
    {
        $this->attributes = $data;
        $this->original   = $data;
    }
}
