<?php
namespace Core\Support\Session;


/**
 * @link https://www.php.net/manual/en/class.sessionhandler
 */
class EncryptHandler extends \SessionHandler
{
    // private $key;

    // public function __construct($key)
    // {
    //     $this->key = $key;
    // }

    public function read($id)
    {
        return parent::read($id);

        // $data = parent::read($id);
        // if (!$data) {
        //     return "";
        // } else {
        //     return decrypt($data, $this->key);
        // }
    }

    public function write($id, $data)
    {
        // $data = encrypt($data, $this->key);
        return parent::write($id, $data);
    }
}
