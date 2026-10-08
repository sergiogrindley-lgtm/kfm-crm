<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IP Whitelist Enforcement
    |--------------------------------------------------------------------------
    | Controls whether access is strictly restricted to authorized office IPs.
    | Set to true in production once the office IPs are configured.
    */
    'whitelist_enabled' => env('IP_WHITELIST_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Allowed Office IP Addresses
    |--------------------------------------------------------------------------
    | List of public IP addresses or CIDR blocks authorized to access KFM CRM:
    | - Oficina Central (Plaza Triunfo, Rota)
    | - Oficina NEX (Base Naval de Rota)
    | - Admin/Dev IPs
    | Can be a comma-separated string in .env (ALLOWED_OFFICE_IPS)
    */
    'allowed_ips' => array_filter(
        array_map('trim', explode(',', env('ALLOWED_OFFICE_IPS', '')))
    ),

    /*
    |--------------------------------------------------------------------------
    | Emergency Bypass Key
    |--------------------------------------------------------------------------
    | Secret key for administrator emergency access via URL query (?bypass=KEY).
    | Setting this cookie allows bypass from non-office IP if needed.
    */
    'bypass_key' => env('SECURITY_BYPASS_KEY', 'kfm-rota-secure-access-2026'),
];
