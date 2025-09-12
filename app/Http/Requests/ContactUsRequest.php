<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'department' => ['required','in:support,sales'],
            'subject'    => ['nullable','string','max:160'],
            'name'       => ['required','string','max:120'],
            'email'      => ['required','email','max:190'],
            'message'    => ['required','string','min:10','max:5000'],
            'agree'      => ['accepted'],
            'file'       => ['nullable','file','mimes:png,jpg,jpeg,pdf,doc,docx','max:4096'], // 4MB
        ];
    }
}

