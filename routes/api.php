<?php

use App\Controllers\ApiController;

return [
    ['GET', '/api/me', [ApiController::class, 'me']],
    ['GET', '/api/users', [ApiController::class, 'users']],
    ['GET', '/api/users/{id:\d+}', [ApiController::class, 'showUser']],
];
