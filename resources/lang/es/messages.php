<?php

return [
    'controllers' => [
        // Mensajes de éxito
        'success' => [
            'records_fetched_successfully' => 'Registros obtenidos con éxito.',
            'record_created_successfully' => 'Registro creado con éxito.',
            'record_updated_successfully' => 'Registro actualizado con éxito.',
            'record_fetched_successfully' => 'Registro obtenido con éxito.',
            'record_deleted_successfully' => 'Registro eliminado con éxito.',
            'record_added_successfully' => 'Registro añadido con éxito.',
        ],
        // Mensajes de error
        'error' => [
            'unexpected_error' => 'Ocurrió un error inesperado al intentar procesar la solicitud.',
        ],
        // Mensajes de advertencia
        'warnings' => [
            'property_field_required' => 'El campo propiedad es obligatorio para usuarios no administradores.',
            'incident_field_required' => 'El campo incident es obligatorio para usuarios no administradores.',
        ],
    ],
];
