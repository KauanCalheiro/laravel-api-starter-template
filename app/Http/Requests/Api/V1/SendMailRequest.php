<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from'           => 'required|array',
            'from.email'     => 'required|email',
            'from.name'      => 'nullable|string',
            'to'             => 'required|array',
            'to.*.email'     => 'email|required',
            'to.*.name'      => 'nullable|string',
            'cc'             => 'nullable|array',
            'cc.*.email'     => 'email',
            'cc.*.name'      => 'nullable|string',
            'bcc'            => 'nullable|array',
            'bcc.*.email'    => 'email',
            'bcc.*.name'     => 'nullable|string',
            'reply_to'       => 'nullable|array',
            'reply_to.email' => 'email',
            'reply_to.name'  => 'nullable|string',
            'subject'        => 'required|string',
            'message'        => 'required|string',
            'attachments'    => 'nullable|array',
        ];
    }
}
