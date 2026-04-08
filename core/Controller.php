<?php

/**
 * Base Controller Class
 * All controllers should extend this class
 */
class Controller
{
    protected $params = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * Render a view with data
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     */
    protected function view($view, $data = [])
    {
        extract($data);
        
        // Convert dot notation to path
        $viewFile = str_replace('.', '/', $view);
        $viewPath = dirname(__DIR__) . "/app/views/{$viewFile}.php";

        if (file_exists($viewPath)) {
            $prevDir = getcwd();
            chdir(dirname(__DIR__) . '/app/views');
            require_once $viewPath;
            chdir($prevDir);
        } else {
            echo "View not found: {$viewFile}";
        }
    }

    /**
     * Redirect to a different URL
     * 
     * @param string $url The URL to redirect to
     */
    protected function redirect($url)
    {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Load a model
     * 
     * @param string $model The model name
     * @return object The model instance
     */
    protected function model($model)
    {
        require_once "../app/models/{$model}.php";
        return new $model();
    }
}
