<?php

use App\Controllers\ApiController;

return [
    ['GET', '/api/me', [ApiController::class, 'me']],
    ['GET', '/api/users', [ApiController::class, 'users']],
    ['GET', '/api/users/{id:\d+}', [ApiController::class, 'showUser']],
    ['POST', '/api/users', [ApiController::class, 'storeUser']],
    ['PUT', '/api/users/{id:\d+}', [ApiController::class, 'updateUser']],
    ['PATCH', '/api/users/{id:\d+}', [ApiController::class, 'updateUser']],
    ['DELETE', '/api/users/{id:\d+}', [ApiController::class, 'deleteUser']],
];
