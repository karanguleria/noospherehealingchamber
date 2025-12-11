<?php
// Debug script to check routes and controllers

echo "<h2>Debug Information</h2>";
echo "<h3>Available Routes:</h3>";
echo "<pre>";
$router = app('router');
$routes = $router->getRoutes();

foreach ($routes as $route) {
    if (strpos($route->uri, 'nova') !== false || 
        strpos($route->uri, 'password') !== false || 
        strpos($route->uri, 'profile') !== false) {
        echo $route->uri . " - " . implode('|', $route->methods) . " - " . $route->action['as'] ?? 'unnamed' . "\n";
    }
}
echo "</pre>";

echo "<h3>Profile Controller Methods:</h3>";
echo "<pre>";
$reflection = new ReflectionClass(\App\Http\Controllers\ProfileController::class);
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

foreach ($methods as $method) {
    if ($method->class == \App\Http\Controllers\ProfileController::class) {
        echo $method->name . "\n";
    }
}
echo "</pre>";

echo "<h3>Current User:</h3>";
echo "<pre>";
if (auth()->check()) {
    $user = auth()->user();
    echo "Logged in as: " . $user->name . " (" . $user->email . ")\n";
} else {
    echo "Not logged in.";
}
echo "</pre>";
