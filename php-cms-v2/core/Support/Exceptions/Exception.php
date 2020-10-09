<?php
namespace Core\Support\Exceptions;

/**
 * Description of Exception
 *
 * @package     Core
 * @author      GEE
 */
class Exception extends \Exception
{
    /**
     * Re-throw Exception
     */
    // public static function rethrow(\Exception $e)
    // {
    //     return new static($e->getMessage(), $e->getCode(), $e);
    // }


    // public function __toString()
    // {
    //     return $this->getMessage();
    // }

    /**
     * https://www.php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    // public function __debugInfo() {
    //     return [
    //         'propSquared' => $this->prop ** 2,
    //     ];
    // }
}
