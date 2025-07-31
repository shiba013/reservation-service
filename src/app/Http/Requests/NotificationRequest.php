<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'send-to' => 'required',
            'subject' => 'required| max:100',
            'body' => 'required| max:5000',
        ];
    }

    public function messages()
    {
        return [
            'send-to.required' => '宛先を選択してください',
            'subject.required' => '件名を入力してください',
            'subject.max' => '件名は100文字以内で入力してください',
            'body.required' => '本文を入力してください',
            'body.max' => '本文は5000文字以内で入力してください',
        ];
    }
}
