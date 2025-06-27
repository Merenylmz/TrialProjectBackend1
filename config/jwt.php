<?php

return [
    'secret' => env('JWT_SECRET'),
    'expire_in' => 3600*2, // saniye cinsinden (örn. 1 saat)
];
