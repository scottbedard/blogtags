<?php
return [
    //
    // plugin
    //
    'plugin' => [
        'name' => 'Blog Tags Erweiterung',
        'description' => 'Fügt Themen zu RainLab.Blog Artikeln hinzu.',
    ],
    //
    // form
    //
    'form' => [
        'label' => 'Themen',
        'name_invalid' => 'Der Themenname darf nur Zahlen, Buchstaben und Bindestriche enthalten.',
        'name_required' => 'Das Themenfeld wird benötigt.',
        'name_unique' => 'Dieses Thema existiert bereits.',
    ],
    //
    // navigation
    //
    'navigation' => [
        'tags' => 'Themen',
    ]
];
