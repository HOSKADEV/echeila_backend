<?php

declare(strict_types=1);

return [
    'accepted'               => 'Le champ :attribute doit être accepté.',
    'accepted_if'            => 'Le champ :attribute doit être accepté quand :other a la valeur :value.',
    'active_url'             => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                  => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal'         => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'alpha'                  => 'Le champ :attribute doit contenir uniquement des lettres.',
    'alpha_dash'             => 'Le champ :attribute doit contenir uniquement des lettres, des chiffres et des tirets.',
    'alpha_num'              => 'Le champ :attribute doit contenir uniquement des chiffres et des lettres.',
    'any_of'                 => 'The :attribute field is invalid.',
    'array'                  => 'Le champ :attribute doit être un tableau.',
    'ascii'                  => 'Le champ :attribute ne doit contenir que des caractères alphanumériques et des symboles codés sur un octet.',
    'before'                 => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'        => 'Le champ :attribute doit être une date antérieure ou égale au :date.',
    'between'                => [
        'array'   => 'Le tableau :attribute doit contenir entre :min et :max éléments.',
        'file'    => 'La taille du fichier de :attribute doit être comprise entre :min et :max kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être comprise entre :min et :max.',
        'string'  => 'Le texte :attribute doit contenir entre :min et :max caractères.',
    ],
    'boolean'                => 'Le champ :attribute doit être vrai ou faux.',
    'can'                    => 'Le champ :attribute contient une valeur non autorisée.',
    'confirmed'              => 'Le champ de confirmation :attribute ne correspond pas.',
    'contains'               => 'Le champ :attribute manque une valeur requise.',
    'current_password'       => 'Le mot de passe est incorrect.',
    'date'                   => 'Le champ :attribute n\'est pas une date valide.',
    'date_equals'            => 'Le champ :attribute doit être une date égale à :date.',
    'date_format'            => 'Le champ :attribute ne correspond pas au format :format.',
    'decimal'                => 'Le champ :attribute doit comporter :decimal décimales.',
    'declined'               => 'Le champ :attribute doit être décliné.',
    'declined_if'            => 'Le champ :attribute doit être décliné quand :other a la valeur :value.',
    'different'              => 'Les champs :attribute et :other doivent être différents.',
    'digits'                 => 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'         => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'dimensions'             => 'La taille de l\'image :attribute n\'est pas conforme.',
    'distinct'               => 'Le champ :attribute a une valeur en double.',
    'doesnt_end_with'        => 'Le champ :attribute ne doit pas finir avec une des valeurs suivantes : :values.',
    'doesnt_start_with'      => 'Le champ :attribute ne doit pas commencer avec une des valeurs suivantes : :values.',
    'email'                  => 'Le champ :attribute doit être une adresse e-mail valide.',
    'ends_with'              => 'Le champ :attribute doit se terminer par une des valeurs suivantes : :values',
    'enum'                   => 'Le champ :attribute sélectionné est invalide.',
    'exists'                 => 'Le champ :attribute sélectionné est invalide.',
    'extensions'             => 'Le champ :attribute doit avoir l\'une des extensions suivantes : :values.',
    'file'                   => 'Le champ :attribute doit être un fichier.',
    'filled'                 => 'Le champ :attribute doit avoir une valeur.',
    'gt'                     => [
        'array'   => 'Le tableau :attribute doit contenir plus de :value éléments.',
        'file'    => 'La taille du fichier de :attribute doit être supérieure à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être supérieure à :value.',
        'string'  => 'Le texte :attribute doit contenir plus de :value caractères.',
    ],
    'gte'                    => [
        'array'   => 'Le tableau :attribute doit contenir au moins :value éléments.',
        'file'    => 'La taille du fichier de :attribute doit être supérieure ou égale à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être supérieure ou égale à :value.',
        'string'  => 'Le texte :attribute doit contenir au moins :value caractères.',
    ],
    'hex_color'              => 'Le champ :attribute doit être une couleur hexadécimale valide.',
    'image'                  => 'Le champ :attribute doit être une image.',
    'in'                     => 'Le champ :attribute est invalide.',
    'in_array'               => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'                => 'Le champ :attribute doit être un entier.',
    'ip'                     => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                   => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                   => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                   => 'Le champ :attribute doit être un document JSON valide.',
    'list'                   => 'Le champ :attribute doit être une liste.',
    'lowercase'              => 'Le champ :attribute doit être en minuscules.',
    'lt'                     => [
        'array'   => 'Le tableau :attribute doit contenir moins de :value éléments.',
        'file'    => 'La taille du fichier de :attribute doit être inférieure à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être inférieure à :value.',
        'string'  => 'Le texte :attribute doit contenir moins de :value caractères.',
    ],
    'lte'                    => [
        'array'   => 'Le tableau :attribute doit contenir au plus :value éléments.',
        'file'    => 'La taille du fichier de :attribute doit être inférieure ou égale à :value kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être inférieure ou égale à :value.',
        'string'  => 'Le texte :attribute doit contenir au plus :value caractères.',
    ],
    'mac_address'            => 'Le champ :attribute doit être une adresse MAC valide.',
    'max'                    => [
        'array'   => 'Le tableau :attribute ne peut pas contenir plus que :max éléments.',
        'file'    => 'La taille du fichier de :attribute ne peut pas dépasser :max kilo-octets.',
        'numeric' => 'La valeur de :attribute ne peut pas être supérieure à :max.',
        'string'  => 'Le texte de :attribute ne peut pas contenir plus de :max caractères.',
    ],
    'max_digits'             => 'Le champ :attribute ne doit pas avoir plus de :max chiffres.',
    'mimes'                  => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'              => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                    => [
        'array'   => 'Le tableau :attribute doit contenir au moins :min éléments.',
        'file'    => 'La taille du fichier de :attribute doit être supérieure ou égale à :min kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être supérieure ou égale à :min.',
        'string'  => 'Le texte de :attribute doit contenir au moins :min caractères.',
    ],
    'min_digits'             => 'Le champ :attribute doit avoir au moins :min chiffres.',
    'missing'                => 'Le champ :attribute doit être manquant.',
    'missing_if'             => 'Le champ :attribute doit être manquant quand :other a la valeur :value.',
    'missing_unless'         => 'Le champ :attribute doit être manquant sauf si :other a la valeur :value.',
    'missing_with'           => 'Le champ :attribute doit être manquant quand :values est présent.',
    'missing_with_all'       => 'Le champ :attribute doit être manquant quand :values sont présents.',
    'multiple_of'            => 'La valeur de :attribute doit être un multiple de :value',
    'not_in'                 => 'Le champ :attribute sélectionné n\'est pas valide.',
    'not_regex'              => 'Le format du champ :attribute n\'est pas valide.',
    'numeric'                => 'Le champ :attribute doit contenir un nombre.',
    'password'               => [
        'letters'       => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed'         => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers'       => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols'       => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'La valeur du champ :attribute est apparue dans une fuite de données. Veuillez choisir une valeur différente.',
    ],
    'present'                => 'Le champ :attribute doit être présent.',
    'present_if'             => 'Le champ :attribute doit être présent lorsque :other est :value.',
    'present_unless'         => 'Le champ :attribute doit être présent sauf si :other vaut :value.',
    'present_with'           => 'Le champ :attribute doit être présent lorsque :values est présent.',
    'present_with_all'       => 'Le champ :attribute doit être présent lorsque :values sont présents.',
    'prohibited'             => 'Le champ :attribute est interdit.',
    'prohibited_if'          => 'Le champ :attribute est interdit quand :other a la valeur :value.',
    'prohibited_if_accepted' => 'Le champ :attribute est interdit quand :other a été accepté.',
    'prohibited_if_declined' => 'Le champ :attribute est interdit quand :other a été refusé.',
    'prohibited_unless'      => 'Le champ :attribute est interdit à moins que :other est l\'une des valeurs :values.',
    'prohibits'              => 'Le champ :attribute interdit :other d\'être présent.',
    'regex'                  => 'Le format du champ :attribute est invalide.',
    'required'               => 'Le champ :attribute est obligatoire.',
    'required_array_keys'    => 'Le champ :attribute doit contenir des entrées pour : :values.',
    'required_if'            => 'Le champ :attribute est obligatoire quand la valeur de :other est :value.',
    'required_if_accepted'   => 'Le champ :attribute est obligatoire quand le champ :other a été accepté.',
    'required_if_declined'   => 'Le champ :attribute est obligatoire quand le champ :other a été refusé.',
    'required_unless'        => 'Le champ :attribute est obligatoire sauf si :other est :values.',
    'required_with'          => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'      => 'Le champ :attribute est obligatoire quand :values sont présents.',
    'required_without'       => 'Le champ :attribute est obligatoire quand :values n\'est pas présent.',
    'required_without_all'   => 'Le champ :attribute est requis quand aucun de :values n\'est présent.',
    'same'                   => 'Les champs :attribute et :other doivent être identiques.',
    'size'                   => [
        'array'   => 'Le tableau :attribute doit contenir :size éléments.',
        'file'    => 'La taille du fichier de :attribute doit être de :size kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être :size.',
        'string'  => 'Le texte de :attribute doit contenir :size caractères.',
    ],
    'starts_with'            => 'Le champ :attribute doit commencer avec une des valeurs suivantes : :values',
    'string'                 => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'               => 'Le champ :attribute doit être un fuseau horaire valide.',
    'ulid'                   => 'Le champ :attribute doit être un ULID valide.',
    'unique'                 => 'La valeur du champ :attribute est déjà utilisée.',
    'uploaded'               => 'Le fichier du champ :attribute n\'a pu être téléversé.',
    'uppercase'              => 'Le champ :attribute doit être en majuscules.',
    'url'                    => 'Le format de l\'URL de :attribute n\'est pas valide.',
    'uuid'                   => 'Le champ :attribute doit être un UUID valide',
    'attributes'             => [],
    'custom'                 => [
        // Messages de validation d'authentification
        'phone' => [
            'required' => 'Le numéro de téléphone est requis.',
            'string' => 'Le numéro de téléphone doit être du texte.',
            'exists' => 'Ce numéro de téléphone n\'est pas enregistré.',
        ],
        'old_password' => [
            'required' => 'Le mot de passe actuel est requis.',
            'string' => 'Le mot de passe actuel doit être du texte.',
        ],
        'new_password' => [
            'required' => 'Le nouveau mot de passe est requis.',
            'string' => 'Le nouveau mot de passe doit être du texte.',
            'min' => 'Le nouveau mot de passe doit contenir au moins 6 caractères.',
            'confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.',
        ],
        'code' => [
            'required' => 'Le code de vérification est requis.',
            'string' => 'Le code de vérification doit être du texte.',
        ],

        // Messages de validation conducteur
        'period' => [
            'required' => 'La période est requise.',
            'in' => 'La période doit être jour, semaine, mois ou année.',
        ],
        'driver_id' => [
            'required' => 'Le conducteur est requis.',
            'exists' => 'Le conducteur sélectionné n\'existe pas.',
        ],

        // Messages de validation objets perdus et trouvés
        'description' => [
            'required' => 'La description est requise.',
            'string' => 'La description doit être du texte.',
        ],
        'image' => [
            'required' => 'L\'image est requise.',
            'image' => 'Le fichier doit être une image.',
            'mimes' => 'L\'image doit être au format jpeg, png, jpg, gif ou svg.',
            'max' => 'La taille de l\'image ne doit pas dépasser 8MB.',
        ],

        // Messages de validation voyage
        'trip_id' => [
            'required' => 'Le voyage est requis.',
            'exists' => 'Le voyage sélectionné n\'existe pas.',
        ],
        'reviewer_type' => [
            'required' => 'Le type d\'évaluateur est requis.',
            'in' => 'Le type d\'évaluateur doit être conducteur ou passager.',
        ],
        'reviewee_id' => [
            'required' => 'La personne à évaluer est requise.',
            'integer' => 'La personne à évaluer doit être un ID valide.',
        ],
        'rating' => [
            'required' => 'La note est requise.',
            'integer' => 'La note doit être un nombre.',
            'min' => 'La note doit être au moins 1 étoile.',
            'max' => 'La note ne peut pas dépasser 5 étoiles.',
        ],
        'comment' => [
            'string' => 'Le commentaire doit être du texte.',
        ],

        // Messages de validation généraux
        'note' => [
            'string' => 'La note doit être du texte.',
            'max' => 'La note ne peut pas dépasser 1000 caractères.',
        ],
        'metadata' => [
            'array' => 'Les métadonnées doivent être dans un format valide.',
        ],
        'total_fees' => [
            'required' => 'Le coût total est requis.',
            'numeric' => 'Le coût total doit être un nombre.',
            'min' => 'Le coût total ne peut pas être négatif.',
        ],

        // Messages spécifiques aux types de voyage
        'ride_type' => [
            'required' => 'Le type de trajet est requis.',
            'string' => 'Le type de trajet doit être du texte.',
            'in' => 'Veuillez sélectionner un type de trajet valide.',
        ],
        'starting_point_id' => [
            'required' => 'Le lieu de départ est requis.',
            'exists' => 'Le lieu de départ sélectionné n\'existe pas.',
        ],
        'arrival_point_id' => [
            'required' => 'La destination est requise.',
            'exists' => 'La destination sélectionnée n\'existe pas.',
        ],
        'number_of_seats' => [
            'required' => 'Le nombre de sièges est requis.',
            'integer' => 'Le nombre de sièges doit être un nombre entier.',
            'min' => 'Au moins 1 siège est requis.',
            'max' => 'Ne peut pas dépasser 8 sièges.',
        ],

        // Messages de coordonnées de localisation
        'starting_point.longitude' => [
            'required' => 'La longitude du point de départ est requise.',
            'numeric' => 'La longitude du point de départ doit être un nombre.',
            'between' => 'La longitude du point de départ doit être des coordonnées valides.',
        ],
        'starting_point.latitude' => [
            'required' => 'La latitude du point de départ est requise.',
            'numeric' => 'La latitude du point de départ doit être un nombre.',
            'between' => 'La latitude du point de départ doit être des coordonnées valides.',
        ],
        'starting_point.name' => [
            'required' => 'Le nom du point de départ est requis.',
            'string' => 'Le nom du point de départ doit être du texte.',
            'max' => 'Le nom du point de départ ne peut pas dépasser 255 caractères.',
        ],
        'arrival_point.longitude' => [
            'required' => 'La longitude de destination est requise.',
            'numeric' => 'La longitude de destination doit être un nombre.',
            'between' => 'La longitude de destination doit être des coordonnées valides.',
        ],
        'arrival_point.latitude' => [
            'required' => 'La latitude de destination est requise.',
            'numeric' => 'La latitude de destination doit être un nombre.',
            'between' => 'La latitude de destination doit être des coordonnées valides.',
        ],
        'arrival_point.name' => [
            'required' => 'Le nom de destination est requis.',
            'string' => 'Le nom de destination doit être du texte.',
            'max' => 'Le nom de destination ne peut pas dépasser 255 caractères.',
        ],

        // Messages spécifiques au dépannage automobile
        'breakdown_point.longitude' => [
            'required' => 'La longitude du lieu de panne est requise.',
            'numeric' => 'La longitude du lieu de panne doit être un nombre.',
            'between' => 'La longitude du lieu de panne doit être des coordonnées valides.',
        ],
        'breakdown_point.latitude' => [
            'required' => 'La latitude du lieu de panne est requise.',
            'numeric' => 'La latitude du lieu de panne doit être un nombre.',
            'between' => 'La latitude du lieu de panne doit être des coordonnées valides.',
        ],
        'breakdown_point.name' => [
            'required' => 'Le nom du lieu de panne est requis.',
            'string' => 'Le nom du lieu de panne doit être du texte.',
            'max' => 'Le nom du lieu de panne ne peut pas dépasser 255 caractères.',
        ],
        'malfunction_type' => [
            'required' => 'Le type de problème est requis.',
            'string' => 'Le type de problème doit être du texte.',
            'in' => 'Veuillez sélectionner un type de problème valide.',
        ],

        // Messages de transport de marchandises
        'pickup_point.longitude' => [
            'required' => 'La longitude du lieu de ramassage est requise.',
            'numeric' => 'La longitude du lieu de ramassage doit être un nombre.',
            'between' => 'La longitude du lieu de ramassage doit être des coordonnées valides.',
        ],
        'pickup_point.latitude' => [
            'required' => 'La latitude du lieu de ramassage est requise.',
            'numeric' => 'La latitude du lieu de ramassage doit être un nombre.',
            'between' => 'La latitude du lieu de ramassage doit être des coordonnées valides.',
        ],
        'pickup_point.name' => [
            'required' => 'Le nom du lieu de ramassage est requis.',
            'string' => 'Le nom du lieu de ramassage doit être du texte.',
            'max' => 'Le nom du lieu de ramassage ne peut pas dépasser 255 caractères.',
        ],
        'delivery_point.longitude' => [
            'required' => 'La longitude du lieu de livraison est requise.',
            'numeric' => 'La longitude du lieu de livraison doit être un nombre.',
            'between' => 'La longitude du lieu de livraison doit être des coordonnées valides.',
        ],
        'delivery_point.latitude' => [
            'required' => 'La latitude du lieu de livraison est requise.',
            'numeric' => 'La latitude du lieu de livraison doit être un nombre.',
            'between' => 'La latitude du lieu de livraison doit être des coordonnées valides.',
        ],
        'delivery_point.name' => [
            'required' => 'Le nom du lieu de livraison est requis.',
            'string' => 'Le nom du lieu de livraison doit être du texte.',
            'max' => 'Le nom du lieu de livraison ne peut pas dépasser 255 caractères.',
        ],
        'delivery_time' => [
            'required' => 'L\'heure de livraison est requise.',
            'date' => 'L\'heure de livraison doit être une date valide.',
            'after' => 'L\'heure de livraison doit être dans le futur.',
        ],
        'cargo.description' => [
            'required' => 'La description de la marchandise est requise.',
            'string' => 'La description de la marchandise doit être du texte.',
            'max' => 'La description de la marchandise ne peut pas dépasser 1000 caractères.',
        ],
        'cargo.weight' => [
            'required' => 'Le poids de la marchandise est requis.',
            'numeric' => 'Le poids de la marchandise doit être un nombre.',
            'min' => 'Le poids de la marchandise doit être au moins 0,1 kg.',
        ],
        'cargo.images' => [
            'array' => 'Les images de marchandise doivent être dans un format valide.',
        ],
        'cargo.images.*' => [
            'image' => 'Chaque image de marchandise doit être une image valide.',
            'mimes' => 'Les images de marchandise doivent être au format jpeg, png, jpg ou gif.',
            'max' => 'Chaque image de marchandise ne doit pas dépasser 8MB.',
        ],

        // Messages de transport d'eau
        'water_type' => [
            'required' => 'Le type d\'eau est requis.',
            'string' => 'Le type d\'eau doit être du texte.',
            'in' => 'Veuillez sélectionner un type d\'eau valide.',
        ],
        'quantity' => [
            'required' => 'La quantité est requise.',
            'numeric' => 'La quantité doit être un nombre.',
            'min' => 'La quantité doit être au moins 0,1.',
        ],

        // Messages de conduite payante
        'starting_time' => [
            'required' => 'L\'heure de départ est requise.',
            'date' => 'L\'heure de départ doit être une date valide.',
            'after' => 'L\'heure de départ doit être dans le futur.',
        ],
        'vehicle_type' => [
            'required' => 'Le type de véhicule est requis.',
            'string' => 'Le type de véhicule doit être du texte.',
            'in' => 'Veuillez sélectionner un type de véhicule valide.',
        ],

        // Messages de voyage international
        'direction' => [
            'required' => 'La direction est requise.',
            'string' => 'La direction doit être du texte.',
            'in' => 'Veuillez sélectionner une direction valide.',
        ],
        'starting_place' => [
            'required' => 'Le lieu de départ est requis.',
            'string' => 'Le lieu de départ doit être du texte.',
            'max' => 'Le lieu de départ ne peut pas dépasser 255 caractères.',
        ],
        'arrival_time' => [
            'required' => 'L\'heure d\'arrivée est requise.',
            'date' => 'L\'heure d\'arrivée doit être une date valide.',
            'after' => 'L\'heure d\'arrivée doit être après l\'heure de départ.',
        ],
        'total_seats' => [
            'required' => 'Le nombre total de sièges est requis.',
            'integer' => 'Le nombre total de sièges doit être un nombre entier.',
            'min' => 'Au moins 1 siège est requis.',
            'max' => 'Ne peut pas dépasser 50 sièges.',
        ],
        'seat_price' => [
            'required' => 'Le prix du siège est requis.',
            'numeric' => 'Le prix du siège doit être un nombre.',
            'min' => 'Le prix du siège ne peut pas être négatif.',
        ],
    ],
];
