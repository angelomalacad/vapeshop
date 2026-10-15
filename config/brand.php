<?php

return [
    'override' => env('BRAND_OVERRIDE', false),
    'name'     => 'Vape Shop',
    'logo'     => 'images/logo-vape.png',

    // Text to hide while the override is ON.
    // Key = what to find, Value = what to show instead.
    // Case-insensitive, whole words only. Longest/most specific FIRST.
    'hide' => [
        // ============ BRANCHES (longest first) ============
        'Asia 1 Branch'                                          => 'Branch 1',
        'Blk 67 Lot 1, Canlubang, Calamba, Laguna'               => '',
        'Majada Out Branch'                                      => 'Branch 2',
        'EFG Building, Majada Out Road'                          => '',
        'MCDC Branch'                                            => 'Branch 3',
        'Blk 1 Lot 10, Canlubang, Calamba, Laguna'               => '',
        'Paciano V2 Branch'                                      => 'Branch 5',
        'Paciano Branch'                                         => 'Branch 4',
        '215 National Road, Brgy. Paciano Rizal, Calamba City'   => '',
        '39 Mayapa, Canlubang Cadre Road, Calamba'               => '',
        'Since 2024'                                             => '',
        'Since 2025'                                             => '',

        // ============ PEOPLE ============
        'Carlo Caranto' => 'Owner',
        'carlo.caranto' => 'owner',
        'carlocaranto'  => 'owner',
        'Carlo'         => 'Owner',

        'Karl Viscaino' => 'User 1',
        'karl.viscaino' => 'user1',
        'karlviscaino'  => 'user1',
        'Karl'          => 'User 1',

        'Mhark Apoliga' => 'User 2',
        'mhark.apoliga' => 'user2',
        'mharkapoliga'  => 'user2',
        'Mhark'         => 'User 2',

        'Rocky Ace' => 'User 3',
        'rocky.ace' => 'user3',
        'rockyace'  => 'user3',
        'Rocky'     => 'User 3',

        'Jeremy Abustan' => 'User 4',
        'jeremy.abustan' => 'user4',
        'jeremyabustan'  => 'user4',
        'Jeremy'         => 'User 4',

        'Rhe Ann Alqueza' => 'User 5',
        'rhe.ann.alqueza' => 'user5',
        'rheannalqueza'   => 'user5',
        'Rhe Ann'         => 'User 5',
    ],
];
