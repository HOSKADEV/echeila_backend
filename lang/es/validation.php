<?php

declare(strict_types=1);

return [
    'accepted'               => 'El campo :attribute debe ser aceptado.',
    'accepted_if'            => 'El campo :attribute debe ser aceptado cuando :other sea :value.',
    'active_url'             => 'El campo :attribute debe ser una URL válida.',
    'after'                  => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'         => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                  => 'El campo :attribute solo debe contener letras.',
    'alpha_dash'             => 'El campo :attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'              => 'El campo :attribute solo debe contener letras y números.',
    'any_of'                 => 'El campo :attribute no es válido.',
    'array'                  => 'El campo :attribute debe ser un arreglo.',
    'ascii'                  => 'El campo :attribute solo debe contener caracteres alfanuméricos y símbolos de un solo byte.',
    'before'                 => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'        => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'                => [
        'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
        'file'    => 'El campo :attribute debe tener entre :min y :max kilobytes.',
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'string'  => 'El campo :attribute debe tener entre :min y :max caracteres.',
    ],
    'boolean'                => 'El campo :attribute debe ser verdadero o falso.',
    'can'                    => 'El campo :attribute contiene un valor no autorizado.',
    'confirmed'              => 'La confirmación de :attribute no coincide.',
    'contains'               => 'Al campo :attribute le falta un valor requerido.',
    'current_password'       => 'La contraseña es incorrecta.',
    'date'                   => 'El campo :attribute debe ser una fecha válida.',
    'date_equals'            => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format'            => 'El campo :attribute debe coincidir con el formato :format.',
    'decimal'                => 'El campo :attribute debe tener :decimal decimales.',
    'declined'               => 'El campo :attribute debe ser rechazado.',
    'declined_if'            => 'El campo :attribute debe ser rechazado cuando :other sea :value.',
    'different'              => 'Los campos :attribute y :other deben ser diferentes.',
    'digits'                 => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'         => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions'             => 'El campo :attribute tiene dimensiones de imagen inválidas.',
    'distinct'               => 'El campo :attribute tiene un valor duplicado.',
    'doesnt_end_with'        => 'El campo :attribute no debe terminar con ninguno de los siguientes: :values.',
    'doesnt_start_with'      => 'El campo :attribute no debe comenzar con ninguno de los siguientes: :values.',
    'email'                  => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with'              => 'El campo :attribute debe terminar con alguno de los siguientes: :values.',
    'enum'                   => 'El :attribute seleccionado no es válido.',
    'exists'                 => 'El :attribute seleccionado no es válido.',
    'extensions'             => 'El campo :attribute debe tener alguna de las siguientes extensiones: :values.',
    'file'                   => 'El campo :attribute debe ser un archivo.',
    'filled'                 => 'El campo :attribute debe tener un valor.',
    'gt'                     => [
        'array'   => 'El campo :attribute debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'string'  => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte'                    => [
        'array'   => 'El campo :attribute debe tener :value elementos o más.',
        'file'    => 'El campo :attribute debe ser mayor o igual que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'string'  => 'El campo :attribute debe tener :value caracteres o más.',
    ],
    'hex_color'              => 'El campo :attribute debe ser un color hexadecimal válido.',
    'image'                  => 'El campo :attribute debe ser una imagen.',
    'in'                     => 'El :attribute seleccionado no es válido.',
    'in_array'               => 'El campo :attribute debe existir en :other.',
    'integer'                => 'El campo :attribute debe ser un número entero.',
    'ip'                     => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                   => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                   => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                   => 'El campo :attribute debe ser una cadena JSON válida.',
    'list'                   => 'El campo :attribute debe ser una lista.',
    'lowercase'              => 'El campo :attribute debe estar en minúsculas.',
    'lt'                     => [
        'array'   => 'El campo :attribute debe tener menos de :value elementos.',
        'file'    => 'El campo :attribute debe ser menor que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'string'  => 'El campo :attribute debe tener menos de :value caracteres.',
    ],
    'lte'                    => [
        'array'   => 'El campo :attribute no debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe ser menor o igual que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
        'string'  => 'El campo :attribute debe tener :value caracteres o menos.',
    ],
    'mac_address'            => 'El campo :attribute debe ser una dirección MAC válida.',
    'max'                    => [
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'max_digits'             => 'El campo :attribute no debe tener más de :max dígitos.',
    'mimes'                  => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'              => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min'                    => [
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
        'file'    => 'El campo :attribute debe ser de al menos :min kilobytes.',
        'numeric' => 'El campo :attribute debe ser de al menos :min.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'min_digits'             => 'El campo :attribute debe tener al menos :min dígitos.',
    'missing'                => 'El campo :attribute debe estar ausente.',
    'missing_if'             => 'El campo :attribute debe estar ausente cuando :other sea :value.',
    'missing_unless'         => 'El campo :attribute debe estar ausente a menos que :other sea :value.',
    'missing_with'           => 'El campo :attribute debe estar ausente cuando :values esté presente.',
    'missing_with_all'       => 'El campo :attribute debe estar ausente cuando :values estén presentes.',
    'multiple_of'            => 'El campo :attribute debe ser un múltiplo de :value.',
    'not_in'                 => 'El :attribute seleccionado no es válido.',
    'not_regex'              => 'El formato del campo :attribute no es válido.',
    'numeric'                => 'El campo :attribute debe ser un número.',
    'password'               => [
        'letters'       => 'El campo :attribute debe contener al menos una letra.',
        'mixed'         => 'El campo :attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => 'El campo :attribute debe contener al menos un número.',
        'symbols'       => 'El campo :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'El :attribute dado ha aparecido en una filtración de datos. Por favor elige otro :attribute.',
    ],
    'present'                => 'El campo :attribute debe estar presente.',
    'present_if'             => 'El campo :attribute debe estar presente cuando :other sea :value.',
    'present_unless'         => 'El campo :attribute debe estar presente a menos que :other sea :value.',
    'present_with'           => 'El campo :attribute debe estar presente cuando :values esté presente.',
    'present_with_all'       => 'El campo :attribute debe estar presente cuando :values estén presentes.',
    'prohibited'             => 'El campo :attribute está prohibido.',
    'prohibited_if'          => 'El campo :attribute está prohibido cuando :other sea :value.',
    'prohibited_if_accepted' => 'El campo :attribute está prohibido cuando :other sea aceptado.',
    'prohibited_if_declined' => 'El campo :attribute está prohibido cuando :other sea rechazado.',
    'prohibited_unless'      => 'El campo :attribute está prohibido a menos que :other esté en :values.',
    'prohibits'              => 'El campo :attribute prohíbe que :other esté presente.',
    'regex'                  => 'El formato del campo :attribute no es válido.',
    'required'               => 'El campo :attribute es obligatorio.',
    'required_array_keys'    => 'El campo :attribute debe contener entradas para: :values.',
    'required_if'            => 'El campo :attribute es obligatorio cuando :other sea :value.',
    'required_if_accepted'   => 'El campo :attribute es obligatorio cuando :other sea aceptado.',
    'required_if_declined'   => 'El campo :attribute es obligatorio cuando :other sea rechazado.',
    'required_unless'        => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'          => 'El campo :attribute es obligatorio cuando :values esté presente.',
    'required_with_all'      => 'El campo :attribute es obligatorio cuando :values estén presentes.',
    'required_without'       => 'El campo :attribute es obligatorio cuando :values no esté presente.',
    'required_without_all'   => 'El campo :attribute es obligatorio cuando ninguno de :values esté presente.',
    'same'                   => 'El campo :attribute debe coincidir con :other.',
    'size'                   => [
        'array'   => 'El campo :attribute debe contener :size elementos.',
        'file'    => 'El campo :attribute debe ser de :size kilobytes.',
        'numeric' => 'El campo :attribute debe ser :size.',
        'string'  => 'El campo :attribute debe tener :size caracteres.',
    ],
    'starts_with'            => 'El campo :attribute debe comenzar con alguno de los siguientes: :values.',
    'string'                 => 'El campo :attribute debe ser una cadena de texto.',
    'timezone'               => 'El campo :attribute debe ser una zona horaria válida.',
    'ulid'                   => 'El campo :attribute debe ser un ULID válido.',
    'unique'                 => 'El :attribute ya ha sido registrado.',
    'uploaded'               => 'No se pudo subir :attribute.',
    'uppercase'              => 'El campo :attribute debe estar en mayúsculas.',
    'url'                    => 'El campo :attribute debe ser una URL válida.',
    'uuid'                   => 'El campo :attribute debe ser un UUID válido.',
    'attributes'             => [],
    'custom'                 => [
        // Mensajes de validación de autenticación
        'phone' => [
            'required' => 'El número de teléfono es obligatorio.',
            'string' => 'El número de teléfono debe ser un texto.',
            'exists' => 'Este número de teléfono no está registrado.',
        ],
        'old_password' => [
            'required' => 'La contraseña actual es obligatoria.',
            'string' => 'La contraseña actual debe ser un texto.',
        ],
        'new_password' => [
            'required' => 'La nueva contraseña es obligatoria.',
            'string' => 'La nueva contraseña debe ser un texto.',
            'min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ],
        'code' => [
            'required' => 'El código de verificación es obligatorio.',
            'string' => 'El código de verificación debe ser un texto.',
        ],

        // Mensajes de validación del conductor
        'period' => [
            'required' => 'El período de tiempo es obligatorio.',
            'in' => 'El período de tiempo debe ser día, semana, mes o año.',
        ],
        'driver_id' => [
            'required' => 'El conductor es obligatorio.',
            'exists' => 'El conductor seleccionado no existe.',
        ],

        // Mensajes de validación de objetos perdidos
        'description' => [
            'required' => 'La descripción es obligatoria.',
            'string' => 'La descripción debe ser un texto.',
        ],
        'image' => [
            'required' => 'La imagen es obligatoria.',
            'image' => 'El archivo debe ser una imagen.',
            'mimes' => 'La imagen debe ser de formato jpeg, png, jpg, gif o svg.',
            'max' => 'El tamaño de la imagen no debe exceder 8MB.',
        ],

        // Mensajes de validación de viaje
        'trip_id' => [
            'required' => 'El viaje es obligatorio.',
            'exists' => 'El viaje seleccionado no existe.',
        ],
        'reviewer_type' => [
            'required' => 'El tipo de evaluador es obligatorio.',
            'in' => 'El tipo de evaluador debe ser conductor o pasajero.',
        ],
        'reviewee_id' => [
            'required' => 'La persona a evaluar es obligatoria.',
            'integer' => 'La persona a evaluar debe tener un ID válido.',
        ],
        'rating' => [
            'required' => 'La calificación es obligatoria.',
            'integer' => 'La calificación debe ser un número.',
            'min' => 'La calificación debe ser de al menos 1 estrella.',
            'max' => 'La calificación no puede exceder 5 estrellas.',
        ],
        'comment' => [
            'string' => 'El comentario debe ser un texto.',
        ],

        // Mensajes generales de validación
        'note' => [
            'string' => 'La nota debe ser un texto.',
            'max' => 'La nota no puede exceder 1000 caracteres.',
        ],
        'metadata' => [
            'array' => 'Los metadatos deben tener un formato válido.',
        ],
        'total_fees' => [
            'required' => 'Las tarifas totales son obligatorias.',
            'numeric' => 'Las tarifas totales deben ser un número.',
            'min' => 'Las tarifas totales no pueden ser negativas.',
        ],

        // Mensajes específicos por tipo de viaje
        'ride_type' => [
            'required' => 'El tipo de viaje es obligatorio.',
            'string' => 'El tipo de viaje debe ser un texto.',
            'in' => 'Seleccione un tipo de viaje válido.',
        ],
        'starting_point_id' => [
            'required' => 'La ubicación de salida es obligatoria.',
            'exists' => 'La ubicación de salida seleccionada no existe.',
        ],
        'arrival_point_id' => [
            'required' => 'El destino es obligatorio.',
            'exists' => 'El destino seleccionado no existe.',
        ],
        'number_of_seats' => [
            'required' => 'La cantidad de asientos es obligatoria.',
            'integer' => 'La cantidad de asientos debe ser un número entero.',
            'min' => 'Se requiere al menos 1 asiento.',
            'max' => 'No puede exceder 8 asientos.',
        ],

        // Mensajes de coordenadas de ubicación
        'starting_point.longitude' => [
            'required' => 'La longitud del punto de salida es obligatoria.',
            'numeric' => 'La longitud del punto de salida debe ser un número.',
            'between' => 'La longitud del punto de salida debe ser una coordenada válida.',
        ],
        'starting_point.latitude' => [
            'required' => 'La latitud del punto de salida es obligatoria.',
            'numeric' => 'La latitud del punto de salida debe ser un número.',
            'between' => 'La latitud del punto de salida debe ser una coordenada válida.',
        ],
        'starting_point.name' => [
            'required' => 'El nombre del punto de salida es obligatorio.',
            'string' => 'El nombre del punto de salida debe ser un texto.',
            'max' => 'El nombre del punto de salida no puede exceder 255 caracteres.',
        ],
        'arrival_point.longitude' => [
            'required' => 'La longitud del destino es obligatoria.',
            'numeric' => 'La longitud del destino debe ser un número.',
            'between' => 'La longitud del destino debe ser una coordenada válida.',
        ],
        'arrival_point.latitude' => [
            'required' => 'La latitud del destino es obligatoria.',
            'numeric' => 'La latitud del destino debe ser un número.',
            'between' => 'La latitud del destino debe ser una coordenada válida.',
        ],
        'arrival_point.name' => [
            'required' => 'El nombre del destino es obligatorio.',
            'string' => 'El nombre del destino debe ser un texto.',
            'max' => 'El nombre del destino no puede exceder 255 caracteres.',
        ],

        // Mensajes específicos de rescate de vehículo
        'breakdown_point.longitude' => [
            'required' => 'La longitud de la ubicación de avería es obligatoria.',
            'numeric' => 'La longitud de la ubicación de avería debe ser un número.',
            'between' => 'La longitud de la ubicación de avería debe ser una coordenada válida.',
        ],
        'breakdown_point.latitude' => [
            'required' => 'La latitud de la ubicación de avería es obligatoria.',
            'numeric' => 'La latitud de la ubicación de avería debe ser un número.',
            'between' => 'La latitud de la ubicación de avería debe ser una coordenada válida.',
        ],
        'breakdown_point.name' => [
            'required' => 'El nombre de la ubicación de avería es obligatorio.',
            'string' => 'El nombre de la ubicación de avería debe ser un texto.',
            'max' => 'El nombre de la ubicación de avería no puede exceder 255 caracteres.',
        ],
        'malfunction_type' => [
            'required' => 'El tipo de problema es obligatorio.',
            'string' => 'El tipo de problema debe ser un texto.',
            'in' => 'Seleccione un tipo de problema válido.',
        ],

        // Mensajes de transporte de carga
        'pickup_point.longitude' => [
            'required' => 'La longitud de la ubicación de recogida es obligatoria.',
            'numeric' => 'La longitud de la ubicación de recogida debe ser un número.',
            'between' => 'La longitud de la ubicación de recogida debe ser una coordenada válida.',
        ],
        'pickup_point.latitude' => [
            'required' => 'La latitud de la ubicación de recogida es obligatoria.',
            'numeric' => 'La latitud de la ubicación de recogida debe ser un número.',
            'between' => 'La latitud de la ubicación de recogida debe ser una coordenada válida.',
        ],
        'pickup_point.name' => [
            'required' => 'El nombre de la ubicación de recogida es obligatorio.',
            'string' => 'El nombre de la ubicación de recogida debe ser un texto.',
            'max' => 'El nombre de la ubicación de recogida no puede exceder 255 caracteres.',
        ],
        'delivery_point.longitude' => [
            'required' => 'La longitud de la ubicación de entrega es obligatoria.',
            'numeric' => 'La longitud de la ubicación de entrega debe ser un número.',
            'between' => 'La longitud de la ubicación de entrega debe ser una coordenada válida.',
        ],
        'delivery_point.latitude' => [
            'required' => 'La latitud de la ubicación de entrega es obligatoria.',
            'numeric' => 'La latitud de la ubicación de entrega debe ser un número.',
            'between' => 'La latitud de la ubicación de entrega debe ser una coordenada válida.',
        ],
        'delivery_point.name' => [
            'required' => 'El nombre de la ubicación de entrega es obligatorio.',
            'string' => 'El nombre de la ubicación de entrega debe ser un texto.',
            'max' => 'El nombre de la ubicación de entrega no puede exceder 255 caracteres.',
        ],
        'delivery_time' => [
            'required' => 'La hora de entrega es obligatoria.',
            'date' => 'La hora de entrega debe ser una fecha válida.',
            'after' => 'La hora de entrega debe ser una fecha futura.',
        ],
        'cargo.description' => [
            'required' => 'La descripción de la carga es obligatoria.',
            'string' => 'La descripción de la carga debe ser un texto.',
            'max' => 'La descripción de la carga no puede exceder 1000 caracteres.',
        ],
        'cargo.weight' => [
            'required' => 'El peso de la carga es obligatorio.',
            'numeric' => 'El peso de la carga debe ser un número.',
            'min' => 'El peso de la carga debe ser de al menos 0.1 kg.',
        ],
        'cargo.images' => [
            'array' => 'Las imágenes de la carga deben tener un formato válido.',
        ],
        'cargo.images.*' => [
            'image' => 'Cada imagen de la carga debe ser una imagen válida.',
            'mimes' => 'Las imágenes de la carga deben ser de formato jpeg, png, jpg o gif.',
            'max' => 'Cada imagen de la carga no debe exceder 8MB.',
        ],

        // Mensajes de transporte de agua
        'water_type' => [
            'required' => 'El tipo de agua es obligatorio.',
            'string' => 'El tipo de agua debe ser un texto.',
            'in' => 'Seleccione un tipo de agua válido.',
        ],
        'quantity' => [
            'required' => 'La cantidad es obligatoria.',
            'numeric' => 'La cantidad debe ser un número.',
            'min' => 'La cantidad debe ser de al menos 0.1.',
        ],

        // Mensajes de conducción pagada
        'starting_time' => [
            'required' => 'La hora de salida es obligatoria.',
            'date' => 'La hora de salida debe ser una fecha válida.',
            'after' => 'La hora de salida debe ser una fecha posterior a hoy.',
        ],
        'vehicle_type' => [
            'required' => 'El tipo de vehículo es obligatorio.',
            'string' => 'El tipo de vehículo debe ser un texto.',
            'in' => 'Seleccione un tipo de vehículo válido.',
        ],

        // Mensajes de viaje internacional
        'direction' => [
            'required' => 'La dirección es obligatoria.',
            'string' => 'La dirección debe ser un texto.',
            'in' => 'Seleccione una dirección válida.',
        ],
        'starting_place' => [
            'required' => 'El lugar de salida es obligatorio.',
            'string' => 'El lugar de salida debe ser un texto.',
            'max' => 'El lugar de salida no puede exceder 255 caracteres.',
        ],
        'arrival_time' => [
            'required' => 'La hora de llegada es obligatoria.',
            'date' => 'La hora de llegada debe ser una fecha válida.',
            'after' => 'La hora de llegada debe ser una fecha posterior a la hora de salida.',
        ],
        'total_seats' => [
            'required' => 'El total de asientos es obligatorio.',
            'integer' => 'El total de asientos debe ser un número entero.',
            'min' => 'Se requiere al menos 1 asiento.',
            'max' => 'No puede exceder 50 asientos.',
        ],
        'seat_price' => [
            'required' => 'El precio del asiento es obligatorio.',
            'numeric' => 'El precio del asiento debe ser un número.',
            'min' => 'El precio del asiento no puede ser negativo.',
        ],

        // Mensajes de creación de conductor
        'first_name' => [
            'required' => 'El nombre es obligatorio.',
            'string' => 'El nombre debe ser un texto.',
            'max' => 'El nombre no puede exceder 255 caracteres.',
        ],
        'last_name' => [
            'required' => 'El apellido es obligatorio.',
            'string' => 'El apellido debe ser un texto.',
            'max' => 'El apellido no puede exceder 255 caracteres.',
        ],
        'birth_date' => [
            'required' => 'La fecha de nacimiento es obligatoria.',
            'date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'before' => 'La fecha de nacimiento debe ser una fecha anterior a hoy.',
        ],
        'email' => [
            'email' => 'El correo electrónico debe ser una dirección válida.',
        ],

        // Mensajes del vehículo
        'vehicle.model_id' => [
            'required' => 'El modelo del vehículo es obligatorio.',
            'exists' => 'El modelo de vehículo seleccionado no existe.',
        ],
        'vehicle.color_id' => [
            'required' => 'El color del vehículo es obligatorio.',
            'exists' => 'El color de vehículo seleccionado no existe.',
        ],
        'vehicle.production_year' => [
            'required' => 'El año de producción del vehículo es obligatorio.',
            'integer' => 'El año de producción del vehículo debe ser un número.',
            'min' => 'El año de producción del vehículo debe ser al menos 1900.',
            'max' => 'El año de producción del vehículo no puede ser futuro.',
        ],
        'vehicle.plate_number' => [
            'required' => 'El número de placa del vehículo es obligatorio.',
            'string' => 'El número de placa del vehículo debe ser un texto.',
            'max' => 'El número de placa del vehículo no puede exceder 255 caracteres.',
            'unique' => 'Este número de placa ya está registrado.',
        ],
        'vehicle.image' => [
            'image' => 'La imagen del vehículo debe ser una imagen válida.',
            'max' => 'La imagen del vehículo no debe exceder 8MB.',
        ],
        'vehicle.permit' => [
            'file' => 'El permiso del vehículo debe ser un archivo.',
            'mimes' => 'El permiso del vehículo debe ser un archivo PDF, JPEG, PNG o JPG.',
            'max' => 'El permiso del vehículo no debe exceder 8MB.',
        ],

        // Mensajes de servicios
        'services' => [
            'required' => 'Se requiere al menos un servicio.',
            'array' => 'Los servicios deben tener un formato válido.',
            'min' => 'Se debe seleccionar al menos un servicio.',
        ],
        'services.*' => [
            'required' => 'El tipo de servicio es obligatorio.',
            'string' => 'El tipo de servicio debe ser un texto.',
            'in' => 'Seleccione un tipo de servicio válido.',
        ],

        // Mensajes de tarjeta
        'cards.national_id.number' => [
            'required' => 'El número del documento nacional de identidad es obligatorio.',
            'string' => 'El número del documento nacional de identidad debe ser un texto.',
            'max' => 'El número del documento nacional de identidad no puede exceder 255 caracteres.',
            'unique' => 'Este número de documento ya está registrado.',
        ],
        'cards.national_id.expiration_date' => [
            'required' => 'La fecha de vencimiento del documento nacional de identidad es obligatoria.',
            'date' => 'La fecha de vencimiento del documento nacional de identidad debe ser una fecha válida.',
            'after' => 'La fecha de vencimiento del documento nacional de identidad debe ser posterior a hoy.',
        ],
        'cards.national_id.front_image' => [
            'required' => 'La imagen frontal del documento nacional de identidad es obligatoria.',
            'image' => 'La imagen frontal del documento nacional de identidad debe ser una imagen válida.',
            'max' => 'La imagen frontal del documento nacional de identidad no debe exceder 8MB.',
        ],
        'cards.national_id.back_image' => [
            'required' => 'La imagen posterior del documento nacional de identidad es obligatoria.',
            'image' => 'La imagen posterior del documento nacional de identidad debe ser una imagen válida.',
            'max' => 'La imagen posterior del documento nacional de identidad no debe exceder 8MB.',
        ],
        'cards.driving_license.number' => [
            'required' => 'El número de la licencia de conducir es obligatorio.',
            'string' => 'El número de la licencia de conducir debe ser un texto.',
            'max' => 'El número de la licencia de conducir no puede exceder 255 caracteres.',
            'unique' => 'Este número de licencia ya está registrado.',
        ],
        'cards.driving_license.expiration_date' => [
            'required' => 'La fecha de vencimiento de la licencia de conducir es obligatoria.',
            'date' => 'La fecha de vencimiento de la licencia de conducir debe ser una fecha válida.',
            'after' => 'La fecha de vencimiento de la licencia de conducir debe ser posterior a hoy.',
        ],
        'cards.driving_license.front_image' => [
            'required' => 'La imagen frontal de la licencia de conducir es obligatoria.',
            'image' => 'La imagen frontal de la licencia de conducir debe ser una imagen válida.',
            'max' => 'La imagen frontal de la licencia de conducir no debe exceder 8MB.',
        ],
        'cards.driving_license.back_image' => [
            'required' => 'La imagen posterior de la licencia de conducir es obligatoria.',
            'image' => 'La imagen posterior de la licencia de conducir debe ser una imagen válida.',
            'max' => 'La imagen posterior de la licencia de conducir no debe exceder 8MB.',
        ],

        // Mensajes de estado
        'status' => [
            'string' => 'El estado debe ser un texto.',
            'in' => 'Seleccione un estado válido.',
        ],

        // Mensajes de nombre completo
        'fullname' => [
            'required_with' => 'El nombre completo es obligatorio cuando se proporciona el número de teléfono.',
            'string' => 'El nombre completo debe ser un texto.',
            'max' => 'El nombre completo no puede exceder 255 caracteres.',
        ],

    ],
];
