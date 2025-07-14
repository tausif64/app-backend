<?php
class Router {
    private $routes = [];

    // Add a route
    public function addRoute($method, $uri, $callback) {
        $this->routes[$method][$uri] = $callback;
    }

    // Handle the incoming request
    public function handleRequest($uri, $method) {
        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
        } else {
            http_response_code(404);
            echo "404 Not Route Found";
        }
    }
}
?>