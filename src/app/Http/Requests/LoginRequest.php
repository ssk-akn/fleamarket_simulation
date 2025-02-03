<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'login' => ['required'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function message()
    {
        return [
            'login.required' => 'お名前またはメールアドレスを入力してください',
            'password.required' => 'パスワードくを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }

    public function failedValidation(\Illuminate\Contacts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        if (!$errors->has('login') && !$errors->has('password')) {
            $validator->errors()->add('login', 'ログイン情報が登録されていません。');
        }
        parent::failedValidation($validator);
    }
}
