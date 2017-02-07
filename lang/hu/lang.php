<?php
return [
    //
    // plugin
    //
    'plugin' => [
        'name' => 'Blog címkék',
        'description' => 'Kiegészítő a RainLab.Blog bővítményhez.',
    ],

    //
    // form
    //
    'form' => [
        'label' => 'Címkék',
        'name_invalid' => 'A címke csak betűket, számokat és kötőjeleket tartalmazhat.',
        'name_required' => 'A címke mező megadása kötelező.',
        'name_unique' => 'A megadott címke már létezik.',
    ],

    //
    // navigation
    //
    'navigation' => [
        'tags' => 'Címkék',
    ]
];
