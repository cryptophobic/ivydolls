<?php

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '/dolls/<dollName:[\w-+\s]+>' => '/dolls/index',
        '<module:admin>' => '<module>',
    ],
];
