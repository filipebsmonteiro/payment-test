<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    private $rules = [];

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
        switch ($this->route()->getName()) {
            case "user.store":
                $this->rules = [
                    'name' => 'required|min:3',
                    'document' => 'required|min:11|unique:users,document',
                    'email' => 'required|email:rfc|unique:users,email',
                    'password' => 'required|min:3'
                ];
                break;
            case "user.update":
                $this->rules = [
                    'name' => 'min:3',
                    'email' => 'email:rfc|unique:users,email',
                    'password' => 'min:3'
                ];
                break;
        }

        return $this->rules;
    }
}
