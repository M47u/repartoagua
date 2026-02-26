<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mapbox Access Token
    |--------------------------------------------------------------------------
    |
    | Tu token de acceso de Mapbox para usar la API de Directions y los mapas.
    | Obtén uno gratis en: https://account.mapbox.com/
    |
    */

    'access_token' => env('MAPBOX_ACCESS_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Mapbox API Base URL
    |--------------------------------------------------------------------------
    |
    | La URL base para las peticiones a la API de Mapbox.
    |
    */

    'api_base_url' => 'https://api.mapbox.com',

    /*
    |--------------------------------------------------------------------------
    | Direcciones API Version
    |--------------------------------------------------------------------------
    |
    | Versión de la API de Directions a usar.
    |
    */

    'directions_version' => 'v5',

    /*
    |--------------------------------------------------------------------------
    | Perfil de Routing por Defecto
    |--------------------------------------------------------------------------
    |
    | Perfil de routing a usar por defecto. Opciones:
    | - driving-traffic (considera tráfico en tiempo real)
    | - driving (sin considerar tráfico)
    | - walking
    | - cycling
    |
    */

    'default_profile' => 'driving-traffic',

];
