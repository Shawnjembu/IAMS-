<?php

/**
 * Router Class
 * Handles URL routing and dispatching to controllers
 */
class Router
{
    /**
     * Routes configuration
     * @var array
     */
    protected $routes = [];

    /**
     * Current route parameters
     * @var array
     */
    protected $params = [];

    /**
     * Add a route to the routing table
     * 
     * @param string $route The route URL
     * @param array $params Controller, action, and other parameters
     */
    public function add($route, $params = [])
    {
        // Convert route to regex pattern
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Get all routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table
     * 
     * @param string $url The route URL
     * @return bool True if match found
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    /**
     * Get current params
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Dispatch the route and create controller object
     * 
     * @param string $url The route URL
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryString($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "{$controller}Controller";

            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (method_exists($controllerObject, $action)) {
                    $controllerObject->$action();
                } else {
                    $this->notFound();
                }
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    /**
     * Remove query string from URL
     * 
     * @param string $url The URL
     * @return string
     */
    protected function removeQueryString($url)
    {
        if ($url !== '') {
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Convert string to StudlyCaps
     * 
     * @param string $string The string
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert string to camelCase
     * 
     * @param string $string The string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Handle 404 Not Found
     */
    protected function notFound()
    {
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page you are looking for does not exist.</p>";
        exit;
    }
}
