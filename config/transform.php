<?php

return [
    'method' => [
        'GET' => 'GET',
        'POST' => 'POST',
        'PUT' => 'PUT',
        'PATCH' => 'PATCH',
        'DELETE' => 'DELETE',
    ],
    'transform_type' => [
        'json' => 'JSON',
        'xml' => 'XML',
    ],
    'position' => [
        'header' => 'Header',
        'body' => 'Body',
        'url' => 'URL',
    ],
    'data_type' => [
        'string' => 'String',
        'integer' => 'Integer',
        'boolean' => 'Boolean',
        'array' => 'Array',
        'object' => 'Object',
    ],
];
