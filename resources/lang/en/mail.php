<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mailing Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Mailing for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'success'   => 'Email sent successfully!',
    'failed' => [
        'default' => 'Failed to send email',
        'invalid_email' => 'Invalid email address provided',
        'missing_subject' => 'Email subject is required',
        'missing_message' => 'Email message is required',
        'missing_to' => 'At least one recipient email address is required',
        'missing_from' => 'Sender email address is required',
        'invalid_attachment_format' => [
            'name' => 'Name is required for each attachment',
            'type' => 'Type is required for each attachment',
            'mime_type' => 'MIME type is required for each attachment',
            'data'      => 'Binary data is required for each attachment',
        ]
    ],
];
