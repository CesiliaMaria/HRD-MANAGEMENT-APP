protected $routeMiddleware = [
    // ... middleware lainnya
    'role' => \App\Http\Middleware\CheckRole::class,
];