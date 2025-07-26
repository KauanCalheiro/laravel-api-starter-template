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

    'success'   => 'E-mail enviado com sucesso!',
    'failed' => [
        'default' => 'Falha ao enviar o e-mail',
        'invalid_email' => 'Endereço de e-mail fornecido é inválido',
        'missing_subject' => 'O assunto do e-mail é obrigatório',
        'missing_message' => 'A mensagem do e-mail é obrigatória',
        'missing_to' => 'É necessário informar pelo menos um destinatário',
        'missing_from' => 'O endereço de e-mail do remetente é obrigatório',
        'invalid_attachment_format' => [
            'name' => 'O nome é obrigatório para cada anexo',
            'type' => 'O tipo é obrigatório para cada anexo',
            'mime_type' => 'O tipo MIME é obrigatório para cada anexo',
            'data'      => 'Os dados binários são obrigatórios para cada anexo',
        ]
    ],
];
