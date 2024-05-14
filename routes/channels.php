<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('comandas', function ($user) {
    return true; // Permitir que todos los usuarios escuchen este canal
});
