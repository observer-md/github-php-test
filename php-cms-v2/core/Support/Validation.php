<?php
namespace Core\Support;

use Core\Support\Exceptions\ValidationException;

/**
 * Validation class
 * 
 * @package     Core\Support
 * @author      GEE
 */
class Validation
{
    /**
     * Field name
     * @var string
     */
    protected $field    = null;
    /**
     * Field label
     * @var string
     */
    protected $label    = null;
    /**
     * Validation type. [numeric, string, email, ip, date]
     * @var string
     */
    protected $type     = null;
    /**
     * Required field
     * @var bool
     */
    protected $required = false;
    /**
     * Nullable field
     * @var bool
     */
    protected $nullable = true;
    /**
     * Default value
     * @var mixed
     */
    protected $default  = null;
    /**
     * Min value
     * @var int
     */
    protected $min      = 0;
    /**
     * Max value
     * @var int
     */
    protected $max      = 0;
    /**
     * Allowed value
     * @var array
     */
    protected $allow    = [];

    /**
     * Create object
     */
    public function __construct(array $rules)
    {
        $this->field    = $rules['field']    ?? null;
        $this->label    = $rules['label']    ?? null;
        $this->type     = $rules['type']     ?? null;
        $this->required = $rules['required'] ?? false;
        $this->nullable = $rules['nullable'] ?? true;
        $this->default  = $rules['default']  ?? null;
        $this->min      = $rules['min']      ?? 0;
        $this->max      = $rules['max']      ?? 0;
        $this->allow    = $rules['allow']    ?? [];
    }


    /**
     * Validate value and return processed value
     * 
     * @param mixed $value
     * @return mixed
     * @throws ValidationException
     */
    public function validate($value)
    {
        // type method name
        $method = 'validate' . ucfirst($this->type);
        if (!method_exists($this, $method)) {
            $this->throwError('wrong validation type.');
        }

        // set default
        if ($this->default && is_null($value)) {
            $value = $this->default;
        }

        $this->checkRequered($value);
        
        // check if nullable
        if ($this->nullable && is_null($value)) {
            return $value;
        }

        // check type
        $value = $this->{$method}($value);
        
        $this->checkMin($value);
        $this->checkMax($value);
        $this->checkAllow($value);

        return $value;
    }


    /**
     * Validate data set and return errors
     * 
     * @param array $rules  List of rules
     * @param array $data   Data set
     * 
     * @return array
     */
    public static function validateData(array $rules, array &$data)
    {
        $errors = [];
        foreach ($rules as $rule) {
            $field = $rule['field'] ?? null;
            if (!$field) {
                continue;
            }

            try {
                $value = $data[$field] ?? null;
                $data[$field] = (new static($rule))->validate($value);
            } catch (ValidationException $e) {
                $errors[$field] = $e->getMessage();
            }
        }
        return $errors;
    }


    /**
     * Validate numeric
     * 
     * @param mixed $value
     * @return numeric
     */
    protected function validateNumeric($value)
    {
        if (!is_numeric($value)) {
            $this->throwError("value should be numeric.");
        }

        if (is_string($value)) {
            $value = strpos($value, '.') ? floatval($value) : intval($value);
        }
        return $value;
    }


    /**
     * Validate string
     * 
     * @param mixed $value
     * @return string
     */
    protected function validateString($value)
    {
        if (!is_string($value)) {
            $this->throwError("value should be string.");
        }
        return trim(strval($value));
    }


    /**
     * Validate email address
     * 
     * @param mixed $value
     * @return string
     */
    protected function validateEmail($value)
    {
        if (!$value = filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            $this->throwError("value should be email address.");
        }
        return $value;
    }


    /**
     * Validate IP address
     * 
     * @param mixed $value
     * @return string
     */
    protected function validateIp($value)
    {
        if (!$value = filter_var($value, \FILTER_VALIDATE_IP)) {
            $this->throwError("value should be IP address.");
        }
        return $value;
    }


    /**
     * Validate Date
     * 
     * @param mixed $value
     * @return string
     */
    protected function validateDate($value)
    {
        if (!\DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
            $this->throwError("value should be date.");
        }
        return $value;
    }


    /**
     * Check required value
     * 
     * @param mixed $value
     */
    protected function checkRequered($value)
    {
        if ($this->required && empty($value)) {
            $this->throwError("value can't be empty.");
        }
    }


    /**
     * Check min value
     * 
     * @param mixed $value
     */
    protected function checkMin($value)
    {
        if (!$this->min) {
            return;
        }

        if (is_numeric($value)) {
            if ($value < $this->min) {
                $this->throwError("value can't be less than {$this->min}.");
            }
        }

        if (is_string($value)) {
            if (mb_strlen($value) < $this->min) {
                $this->throwError("value can't be shorter than {$this->min} characters.");
            }
        }
    }


    /**
     * Check max value
     * 
     * @param mixed $value
     */
    protected function checkMax($value)
    {
        if (!$this->max) {
            return;
        }

        if (is_numeric($value)) {
            if ($value > $this->max) {
                $this->throwError("value can't be more than {$this->max}.");
            }
        }

        if (is_string($value)) {
            if (mb_strlen($value) > $this->max) {
                $this->throwError("value can't be longer than {$this->max} characters.");
            }
        }
    }


    /**
     * Check allow value
     * 
     * @param mixed $value
     */
    protected function checkAllow($value)
    {
        if (empty($this->allow)) {
            return;
        }

        if (!in_array($value, $this->allow)) {
            $this->throwError("value not allowed.");
        }
    }


    /**
     * Throw check error
     * 
     * @param string $message
     * @throws ValidationException
     */
    protected function throwError(string $message)
    {
        $label = $this->label ?: $this->field;
        throw new ValidationException("Field [{$label}] {$message}");
    }
}
