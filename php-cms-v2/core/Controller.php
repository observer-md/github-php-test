<?php
namespace Core;


/**
 * Controller base class
 * 
 * @package     Core
 * @author      GEE
 */
abstract class Controller
{
    /**
     * HTTP Request
     * @var Request
     */
    protected $request;

     /**
     * HTTP Response
     * @var Response
     */
    protected $response;

    /**
     * View layout name
     * @var string
     */
    protected $layout = '';


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request  = Register::get('request');
        $this->response = Register::get('response');
    }


    /**
     * Load view
     * 
     * @param string $path  View path
     * @param array  $data  View data
     */
    public function view($path, $data = [])
    {

    }


    /**
     * Load partial view
     * 
     * @param string $path  View path
     * @param array  $data  View data
     * 
     * @return string
     */
    public function viewPartial($path, $data = [])
    {
        return '';
    }


    /**
     * Print JSON respone
     * 
     * @param array $data       Data
     * @param int   $status     Http Status
     */
    public function json($data = [], $status = 200)
    {
        $data += [
            'success' => true,
            'message' => '',
        ];

        echo json_encode($data);
        exit;
    }
}
