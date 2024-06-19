<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Student attributes from student csv file
    |--------------------------------------------------------------------------
    |
    | This attributes should be present in csv file when course manager imports
    | new students. Keys represent the name of the attribute and values are actual
    | titles in the first row of csv file.
    |
    */

    'csv-attributes' => [
        'name' => 'ime',
        'surname' => 'prezime',
        'email' => 'email',
        'jmbag' => 'jmbag',
    ],

    /*
    |--------------------------------------------------------------------------
    | New student default password
    |--------------------------------------------------------------------------
    |
    | This password is set as new password for new students that are created
    | when course manager imported new students via csv file upload. Students should
    | change password in the app.
    |
    */

    'default-password' => '12345678',
];
