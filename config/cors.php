<?php 

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Rutas afectadas por CORS
    'allowed_methods' => ['*'], // Métodos permitidos: GET, POST, etc.
    'allowed_origins' => ['*'], // Origen de tu frontend
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Headers permitidos (Content-Type, Authorization, etc.)
    'exposed_headers' => [],
    'max_age' => 0, // Tiempo de caché para preflight requests
    'supports_credentials' => false, // Cambiar a "true" si usas cookies o autenticación
];

?>