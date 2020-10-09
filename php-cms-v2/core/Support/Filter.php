<?php
namespace Core\Support;


/**
 * Filter variable class
 * Class uses to filter variable
 * 
 * @package Core\Support
 * @author  GEE
 */
class Filter
{
    /**
     * Return filtered key value
     * 
     * @param array     $data       Array data
     * @param string    $key        Key name
     * @param array     $default    Default value
     * @param mixed     $filter     Apply filter to value
     * @param mixed     $options    Additional filter options
     * 
     * @return mixed
     */
    public static function param(array $data = null, $key = null, $default = null, $filter = null, $options = null)
    {
        $data = is_array($data) ? $data : [];
        if (!$key) {
            return $data;
        }
        return array_key_exists($key, $data) ? self::value($data[$key], $default, $filter, $options) : $default;
    }


    /**
     * Return filtered value
     * 
     * @param mixed         $value      Value
     * @param mixed         $default    Default Value
     * @param mixed         $filter     Filter value
     *      - <null>:   autodetect filter
     *      - <string>: predefined filter [int, float, string, bool, xss, notags, ip, email, url, array-int, array-string]
     *      - <int>:    uses filter_var()
     *      - <array>:  uses filter_var_array()
     * @param mixed         $options    Filter options. Only for filter_var()
     * 
     * @return mixed
     */
    public static function value($value, $default = null, $filter = null, $options = null)
    {
        $filterName = $filter;

        // auto detect filter, if not set
        if (!$filterName) {
            $filterName = gettype($value);
            if (is_string($value) && is_numeric($value)) {
                $filterName = strpos($value, '.') > 0 ? 'float' : 'int';
            }
        }

        /*
         * Sanitize with filter_var() or filter_var_array()
         * https://www.php.net/manual/en/filter.filters.sanitize.php
         * https://www.php.net/manual/en/filter.filters.validate.php
         */
        if (is_numeric($filter)) {
            $filterName = 'filter_var';
        } else if (is_array($filter)) {
            $filterName = 'filter_var_array';
        }
        
        // apply filter
        switch ($filterName) {
            case 'int':
            case 'integer':
                $value = intval($value);
                break;
            case 'float':
            case 'double':
                $value = floatval($value);
                break;
            case 'str':
            case 'string':
                $value = trim(strval($value));
                $value = filter_var($value, \FILTER_SANITIZE_STRING) ?: $default;
                break;
            case 'notags':
                $value = strval($value);
                $value = trim(strip_tags($value));
                $value = filter_var($value, \FILTER_SANITIZE_STRING) ?: $default;
                break;
            case 'xss':
                // Prevent XSS Attacks
                $value = trim(strval($value));
                $value = htmlspecialchars($value, \ENT_QUOTES | \ENT_HTML5, 'UTF-8');
                break;
            case 'bool':
            case 'boolean':
                if (is_string($value)) {
                    $value = in_array($value, ['true', 'yes', 'y', 'on', '1']) ? true : false;
                }
                $value = boolval($value);
                break;
            case 'ip':
                $value = trim(strval($value));
                $value = filter_var($value, \FILTER_VALIDATE_IP) ?: $default;
                break;
            case 'email':
                $value = trim(strval($value));
                $value = filter_var($value, \FILTER_VALIDATE_EMAIL) ?: $default;
                break;
            case 'url':
                $value = trim(strval($value));
                $value = filter_var($value, \FILTER_VALIDATE_URL) ?: $default;
                break;
            case 'array-int':
                $value = filter_var($value, \FILTER_VALIDATE_INT, \FILTER_REQUIRE_ARRAY) ?: $default;
                $value = array_filter($value);
                break;
            case 'array-string':
                $value = filter_var($value, \FILTER_SANITIZE_STRING, \FILTER_REQUIRE_ARRAY) ?: $default;
                break;
            case 'filter_var':
                $value = filter_var($value, $filter, $options) ?: $default;
                break;
            case 'filter_var_array':
                $value = filter_var_array($value, $filter);
                break;
        }
        
        return $value;
    }
}
