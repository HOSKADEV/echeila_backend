<?php

declare(strict_types=1);

return [
    'accepted'               => 'The :attribute field must be accepted.',
    'accepted_if'            => 'The :attribute field must be accepted when :other is :value.',
    'active_url'             => 'The :attribute field must be a valid URL.',
    'after'                  => 'The :attribute field must be a date after :date.',
    'after_or_equal'         => 'The :attribute field must be a date after or equal to :date.',
    'alpha'                  => 'The :attribute field must only contain letters.',
    'alpha_dash'             => 'The :attribute field must only contain letters, numbers, dashes, and underscores.',
    'alpha_num'              => 'The :attribute field must only contain letters and numbers.',
    'any_of'                 => 'The :attribute field is invalid.',
    'array'                  => 'The :attribute field must be an array.',
    'ascii'                  => 'The :attribute field must only contain single-byte alphanumeric characters and symbols.',
    'before'                 => 'The :attribute field must be a date before :date.',
    'before_or_equal'        => 'The :attribute field must be a date before or equal to :date.',
    'between'                => [
        'array'   => 'The :attribute field must have between :min and :max items.',
        'file'    => 'The :attribute field must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute field must be between :min and :max.',
        'string'  => 'The :attribute field must be between :min and :max characters.',
    ],
    'boolean'                => 'The :attribute field must be true or false.',
    'can'                    => 'The :attribute field contains an unauthorized value.',
    'confirmed'              => 'The :attribute field confirmation does not match.',
    'contains'               => 'The :attribute field is missing a required value.',
    'current_password'       => 'The password is incorrect.',
    'date'                   => 'The :attribute field must be a valid date.',
    'date_equals'            => 'The :attribute field must be a date equal to :date.',
    'date_format'            => 'The :attribute field must match the format :format.',
    'decimal'                => 'The :attribute field must have :decimal decimal places.',
    'declined'               => 'The :attribute field must be declined.',
    'declined_if'            => 'The :attribute field must be declined when :other is :value.',
    'different'              => 'The :attribute field and :other must be different.',
    'digits'                 => 'The :attribute field must be :digits digits.',
    'digits_between'         => 'The :attribute field must be between :min and :max digits.',
    'dimensions'             => 'The :attribute field has invalid image dimensions.',
    'distinct'               => 'The :attribute field has a duplicate value.',
    'doesnt_end_with'        => 'The :attribute field must not end with one of the following: :values.',
    'doesnt_start_with'      => 'The :attribute field must not start with one of the following: :values.',
    'email'                  => 'The :attribute field must be a valid email address.',
    'ends_with'              => 'The :attribute field must end with one of the following: :values.',
    'enum'                   => 'The selected :attribute is invalid.',
    'exists'                 => 'The selected :attribute is invalid.',
    'extensions'             => 'The :attribute field must have one of the following extensions: :values.',
    'file'                   => 'The :attribute field must be a file.',
    'filled'                 => 'The :attribute field must have a value.',
    'gt'                     => [
        'array'   => 'The :attribute field must have more than :value items.',
        'file'    => 'The :attribute field must be greater than :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than :value.',
        'string'  => 'The :attribute field must be greater than :value characters.',
    ],
    'gte'                    => [
        'array'   => 'The :attribute field must have :value items or more.',
        'file'    => 'The :attribute field must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be greater than or equal to :value.',
        'string'  => 'The :attribute field must be greater than or equal to :value characters.',
    ],
    'hex_color'              => 'The :attribute field must be a valid hexadecimal color.',
    'image'                  => 'The :attribute field must be an image.',
    'in'                     => 'The selected :attribute is invalid.',
    'in_array'               => 'The :attribute field must exist in :other.',
    'integer'                => 'The :attribute field must be an integer.',
    'ip'                     => 'The :attribute field must be a valid IP address.',
    'ipv4'                   => 'The :attribute field must be a valid IPv4 address.',
    'ipv6'                   => 'The :attribute field must be a valid IPv6 address.',
    'json'                   => 'The :attribute field must be a valid JSON string.',
    'list'                   => 'The :attribute field must be a list.',
    'lowercase'              => 'The :attribute field must be lowercase.',
    'lt'                     => [
        'array'   => 'The :attribute field must have less than :value items.',
        'file'    => 'The :attribute field must be less than :value kilobytes.',
        'numeric' => 'The :attribute field must be less than :value.',
        'string'  => 'The :attribute field must be less than :value characters.',
    ],
    'lte'                    => [
        'array'   => 'The :attribute field must not have more than :value items.',
        'file'    => 'The :attribute field must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute field must be less than or equal to :value.',
        'string'  => 'The :attribute field must be less than or equal to :value characters.',
    ],
    'mac_address'            => 'The :attribute field must be a valid MAC address.',
    'max'                    => [
        'array'   => 'The :attribute field must not have more than :max items.',
        'file'    => 'The :attribute field must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string'  => 'The :attribute field must not be greater than :max characters.',
    ],
    'max_digits'             => 'The :attribute field must not have more than :max digits.',
    'mimes'                  => 'The :attribute field must be a file of type: :values.',
    'mimetypes'              => 'The :attribute field must be a file of type: :values.',
    'min'                    => [
        'array'   => 'The :attribute field must have at least :min items.',
        'file'    => 'The :attribute field must be at least :min kilobytes.',
        'numeric' => 'The :attribute field must be at least :min.',
        'string'  => 'The :attribute field must be at least :min characters.',
    ],
    'min_digits'             => 'The :attribute field must have at least :min digits.',
    'missing'                => 'The :attribute field must be missing.',
    'missing_if'             => 'The :attribute field must be missing when :other is :value.',
    'missing_unless'         => 'The :attribute field must be missing unless :other is :value.',
    'missing_with'           => 'The :attribute field must be missing when :values is present.',
    'missing_with_all'       => 'The :attribute field must be missing when :values are present.',
    'multiple_of'            => 'The :attribute field must be a multiple of :value.',
    'not_in'                 => 'The selected :attribute is invalid.',
    'not_regex'              => 'The :attribute field format is invalid.',
    'numeric'                => 'The :attribute field must be a number.',
    'password'               => [
        'letters'       => 'The :attribute field must contain at least one letter.',
        'mixed'         => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers'       => 'The :attribute field must contain at least one number.',
        'symbols'       => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present'                => 'The :attribute field must be present.',
    'present_if'             => 'The :attribute field must be present when :other is :value.',
    'present_unless'         => 'The :attribute field must be present unless :other is :value.',
    'present_with'           => 'The :attribute field must be present when :values is present.',
    'present_with_all'       => 'The :attribute field must be present when :values are present.',
    'prohibited'             => 'The :attribute field is prohibited.',
    'prohibited_if'          => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_if_accepted' => 'The :attribute field is prohibited when :other is accepted.',
    'prohibited_if_declined' => 'The :attribute field is prohibited when :other is declined.',
    'prohibited_unless'      => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits'              => 'The :attribute field prohibits :other from being present.',
    'regex'                  => 'The :attribute field format is invalid.',
    'required'               => 'The :attribute field is required.',
    'required_array_keys'    => 'The :attribute field must contain entries for: :values.',
    'required_if'            => 'The :attribute field is required when :other is :value.',
    'required_if_accepted'   => 'The :attribute field is required when :other is accepted.',
    'required_if_declined'   => 'The :attribute field is required when :other is declined.',
    'required_unless'        => 'The :attribute field is required unless :other is in :values.',
    'required_with'          => 'The :attribute field is required when :values is present.',
    'required_with_all'      => 'The :attribute field is required when :values are present.',
    'required_without'       => 'The :attribute field is required when :values is not present.',
    'required_without_all'   => 'The :attribute field is required when none of :values are present.',
    'same'                   => 'The :attribute field must match :other.',
    'size'                   => [
        'array'   => 'The :attribute field must contain :size items.',
        'file'    => 'The :attribute field must be :size kilobytes.',
        'numeric' => 'The :attribute field must be :size.',
        'string'  => 'The :attribute field must be :size characters.',
    ],
    'starts_with'            => 'The :attribute field must start with one of the following: :values.',
    'string'                 => 'The :attribute field must be a string.',
    'timezone'               => 'The :attribute field must be a valid timezone.',
    'ulid'                   => 'The :attribute field must be a valid ULID.',
    'unique'                 => 'The :attribute has already been taken.',
    'uploaded'               => 'The :attribute failed to upload.',
    'uppercase'              => 'The :attribute field must be uppercase.',
    'url'                    => 'The :attribute field must be a valid URL.',
    'uuid'                   => 'The :attribute field must be a valid UUID.',
    'attributes'             => [],
    'custom'                 => [
        // Auth validation messages
        'phone' => [
            'required' => 'Phone number is required.',
            'string' => 'Phone number must be a text.',
            'exists' => 'This phone number is not registered.',
        ],
        'old_password' => [
            'required' => 'Current password is required.',
            'string' => 'Current password must be a text.',
        ],
        'new_password' => [
            'required' => 'New password is required.',
            'string' => 'New password must be a text.',
            'min' => 'New password must be at least 6 characters.',
            'confirmed' => 'New password confirmation does not match.',
        ],
        'code' => [
            'required' => 'Verification code is required.',
            'string' => 'Verification code must be a text.',
        ],

        // Driver validation messages
        'period' => [
            'required' => 'Time period is required.',
            'in' => 'Time period must be day, week, month, or year.',
        ],
        'driver_id' => [
            'required' => 'Driver is required.',
            'exists' => 'Selected driver does not exist.',
        ],

        // Lost and Found validation messages
        'description' => [
            'required' => 'Description is required.',
            'string' => 'Description must be a text.',
        ],
        'image' => [
            'required' => 'Image is required.',
            'image' => 'File must be an image.',
            'mimes' => 'Image must be jpeg, png, jpg, gif, or svg format.',
            'max' => 'Image size must not exceed 8MB.',
        ],

        // Trip validation messages
        'trip_id' => [
            'required' => 'Trip is required.',
            'exists' => 'Selected trip does not exist.',
        ],
        'reviewer_type' => [
            'required' => 'Reviewer type is required.',
            'in' => 'Reviewer type must be driver or passenger.',
        ],
        'reviewee_id' => [
            'required' => 'Person to review is required.',
            'integer' => 'Person to review must be a valid ID.',
        ],
        'rating' => [
            'required' => 'Rating is required.',
            'integer' => 'Rating must be a number.',
            'min' => 'Rating must be at least 1 star.',
            'max' => 'Rating cannot exceed 5 stars.',
        ],
        'comment' => [
            'string' => 'Comment must be a text.',
        ],

        // General validation messages
        'note' => [
            'string' => 'Note must be a text.',
            'max' => 'Note cannot exceed 1000 characters.',
        ],
        'metadata' => [
            'array' => 'Metadata must be a valid format.',
        ],
        'total_fees' => [
            'required' => 'Total fees is required.',
            'numeric' => 'Total fees must be a number.',
            'min' => 'Total fees cannot be negative.',
        ],

        // Trip type specific messages
        'ride_type' => [
            'required' => 'Ride type is required.',
            'string' => 'Ride type must be a text.',
            'in' => 'Please select a valid ride type.',
        ],
        'starting_point_id' => [
            'required' => 'Starting location is required.',
            'exists' => 'Selected starting location does not exist.',
        ],
        'arrival_point_id' => [
            'required' => 'Destination is required.',
            'exists' => 'Selected destination does not exist.',
        ],
        'number_of_seats' => [
            'required' => 'Number of seats is required.',
            'integer' => 'Number of seats must be a whole number.',
            'min' => 'At least 1 seat is required.',
            'max' => 'Cannot exceed 8 seats.',
        ],

        // Location coordinate messages
        'starting_point.longitude' => [
            'required' => 'Starting point longitude is required.',
            'numeric' => 'Starting point longitude must be a number.',
            'between' => 'Starting point longitude must be valid coordinates.',
        ],
        'starting_point.latitude' => [
            'required' => 'Starting point latitude is required.',
            'numeric' => 'Starting point latitude must be a number.',
            'between' => 'Starting point latitude must be valid coordinates.',
        ],
        'starting_point.name' => [
            'required' => 'Starting point name is required.',
            'string' => 'Starting point name must be a text.',
            'max' => 'Starting point name cannot exceed 255 characters.',
        ],
        'arrival_point.longitude' => [
            'required' => 'Destination longitude is required.',
            'numeric' => 'Destination longitude must be a number.',
            'between' => 'Destination longitude must be valid coordinates.',
        ],
        'arrival_point.latitude' => [
            'required' => 'Destination latitude is required.',
            'numeric' => 'Destination latitude must be a number.',
            'between' => 'Destination latitude must be valid coordinates.',
        ],
        'arrival_point.name' => [
            'required' => 'Destination name is required.',
            'string' => 'Destination name must be a text.',
            'max' => 'Destination name cannot exceed 255 characters.',
        ],

        // Car rescue specific messages
        'breakdown_point.longitude' => [
            'required' => 'Breakdown location longitude is required.',
            'numeric' => 'Breakdown location longitude must be a number.',
            'between' => 'Breakdown location longitude must be valid coordinates.',
        ],
        'breakdown_point.latitude' => [
            'required' => 'Breakdown location latitude is required.',
            'numeric' => 'Breakdown location latitude must be a number.',
            'between' => 'Breakdown location latitude must be valid coordinates.',
        ],
        'breakdown_point.name' => [
            'required' => 'Breakdown location name is required.',
            'string' => 'Breakdown location name must be a text.',
            'max' => 'Breakdown location name cannot exceed 255 characters.',
        ],
        'malfunction_type' => [
            'required' => 'Problem type is required.',
            'string' => 'Problem type must be a text.',
            'in' => 'Please select a valid problem type.',
        ],

        // Cargo transport messages
        'pickup_point.longitude' => [
            'required' => 'Pickup location longitude is required.',
            'numeric' => 'Pickup location longitude must be a number.',
            'between' => 'Pickup location longitude must be valid coordinates.',
        ],
        'pickup_point.latitude' => [
            'required' => 'Pickup location latitude is required.',
            'numeric' => 'Pickup location latitude must be a number.',
            'between' => 'Pickup location latitude must be valid coordinates.',
        ],
        'pickup_point.name' => [
            'required' => 'Pickup location name is required.',
            'string' => 'Pickup location name must be a text.',
            'max' => 'Pickup location name cannot exceed 255 characters.',
        ],
        'delivery_point.longitude' => [
            'required' => 'Delivery location longitude is required.',
            'numeric' => 'Delivery location longitude must be a number.',
            'between' => 'Delivery location longitude must be valid coordinates.',
        ],
        'delivery_point.latitude' => [
            'required' => 'Delivery location latitude is required.',
            'numeric' => 'Delivery location latitude must be a number.',
            'between' => 'Delivery location latitude must be valid coordinates.',
        ],
        'delivery_point.name' => [
            'required' => 'Delivery location name is required.',
            'string' => 'Delivery location name must be a text.',
            'max' => 'Delivery location name cannot exceed 255 characters.',
        ],
        'delivery_time' => [
            'required' => 'Delivery time is required.',
            'date' => 'Delivery time must be a valid date.',
            'after' => 'Delivery time must be in the future.',
        ],
        'cargo.description' => [
            'required' => 'Cargo description is required.',
            'string' => 'Cargo description must be a text.',
            'max' => 'Cargo description cannot exceed 1000 characters.',
        ],
        'cargo.weight' => [
            'required' => 'Cargo weight is required.',
            'numeric' => 'Cargo weight must be a number.',
            'min' => 'Cargo weight must be at least 0.1 kg.',
        ],
        'cargo.images' => [
            'array' => 'Cargo images must be a valid format.',
        ],
        'cargo.images.*' => [
            'image' => 'Each cargo image must be a valid image.',
            'mimes' => 'Cargo images must be jpeg, png, jpg, or gif format.',
            'max' => 'Each cargo image must not exceed 8MB.',
        ],

        // Water transport messages
        'water_type' => [
            'required' => 'Water type is required.',
            'string' => 'Water type must be a text.',
            'in' => 'Please select a valid water type.',
        ],
        'quantity' => [
            'required' => 'Quantity is required.',
            'numeric' => 'Quantity must be a number.',
            'min' => 'Quantity must be at least 0.1.',
        ],

        // Paid driving messages
        'starting_time' => [
            'required' => 'Starting time is required.',
            'date' => 'Starting time must be a valid date.',
            'after' => 'Starting time must be in the future.',
        ],
        'vehicle_type' => [
            'required' => 'Vehicle type is required.',
            'string' => 'Vehicle type must be a text.',
            'in' => 'Please select a valid vehicle type.',
        ],

        // International trip messages
        'direction' => [
            'required' => 'Direction is required.',
            'string' => 'Direction must be a text.',
            'in' => 'Please select a valid direction.',
        ],
        'starting_place' => [
            'required' => 'Starting place is required.',
            'string' => 'Starting place must be a text.',
            'max' => 'Starting place cannot exceed 255 characters.',
        ],
        'arrival_time' => [
            'required' => 'Arrival time is required.',
            'date' => 'Arrival time must be a valid date.',
            'after' => 'Arrival time must be after starting time.',
        ],
        'total_seats' => [
            'required' => 'Total seats is required.',
            'integer' => 'Total seats must be a whole number.',
            'min' => 'At least 1 seat is required.',
            'max' => 'Cannot exceed 50 seats.',
        ],
        'seat_price' => [
            'required' => 'Seat price is required.',
            'numeric' => 'Seat price must be a number.',
            'min' => 'Seat price cannot be negative.',
        ],
    ],
];
