<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // Agrega esta línea exacta:
    MongoDB\Laravel\MongoDBServiceProvider::class,
];
