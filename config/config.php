<?php

return [
    'name' => 'Authentication',
    'register_provides_token' => env('REGISTER_PROVIDES_TOKEN', true),
    'register_must_confirm_email' => env('REGISTER_MUST_CONFIRM_EMAIL', true),
];
