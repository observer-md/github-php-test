<?php

/**
 * Base controller class
 */
abstract class BaseController
{
    /**
     * View layout name
     * @var string $layout
     */
    protected $layout = "layout";


    /**
     * Load view
     * 
     * @param string $view  View Name
     * @param array $data   View Data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewPath = DIR_APP . "/views/{$view}.php";

        // load page view
        ob_start();
        include($viewPath);
        $context = ob_get_clean();
        
        // load layout
        $layoutPath = DIR_APP . "/views/{$this->layout}.php";
        require $layoutPath;
    }

    /**
     * Render JSON
     * 
     * @param array $data       Data array
     * @param int   $status     Request status
     */
    public function renderJson(array $data, $status = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
