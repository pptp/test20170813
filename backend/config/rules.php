<?php

return [
    [
        'pattern' => 'customers/search/<search:.+>',
        'verb' => 'GET',
        'route' => 'customers/search'
    ],
    [
        'pattern' => 'transaction/download',
        'verb' => 'GET',
        'route' => 'transaction/download'
    ],
];