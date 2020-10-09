<?php
namespace Core\Support\Session;


/**
 * @link https://www.php.net/manual/en/class.sessionhandler
 */
class CacheHandler extends \SessionHandler
{
    /**
     * Close the session
     * @return bool
     */
    public function close() : bool
    {
        return parent::close();
    }


    /**
     * Return a new session ID
     * @return string
     */
    public function create_sid() : string
    {
        return parent::create_sid();
    }


    /**
     * Destroy a session
     * @return bool
     */
    public function destroy($session_id) : bool
    {
        return parent::destroy($session_id);
    }


    /**
     * Cleanup old sessions
     * @return int
     */
    public function gc($maxlifetime) : int
    {
        return parent::gc($maxlifetime);
    }


    /**
     * Initialize session
     * @return bool
     */
    public function open($save_path, $session_name) : bool
    {
        return parent::open($save_path, $session_name);
    }


    /**
     * Read session data
     * @return string
     */
    public function read($session_id) : string
    {
        return parent::read($session_id);
    }


    /**
     * Write session data
     * @return bool
     */
    public function write($session_id, $session_data) : bool
    {
        return parent::write($session_id, $session_data);
    }
}


// $handler = new EncryptedSessionHandler($key);
// session_set_save_handler($handler, true);
// session_start();
