<?php
return [
    //
    // plugin
    //
    'plugin' => [
        'name' => 'Blog Tags Extension',
        'description' => 'Adds tagging to RainLab.Blog posts.',
    ],

    //
    // form
    //
    'form' => [
        'label' => 'Tags',
        'name_invalid' => 'Tag names may only contain alpha-numeric characters and hyphens.',
        'name_required' => 'The tag field is required.',
        'name_unique' => 'That tag name is already taken.',
    ],

    //
    // navigation
    //
    'navigation' => [
        'tags' => 'Tags',
    ]
];
