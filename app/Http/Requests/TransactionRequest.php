<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public $rules = [];

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
            case "transaction.transfer":
                $this->rules = [
                    'origin_email'      => 'required_without_all:origin_document,origin_account_id',
                    'origin_document'   => 'required_without_all:origin_email,origin_account_id',
                    'origin_account_id' => 'required_without_all:origin_email,origin_document',

                    'destination_email'         => 'required_without_all:destination_document,destination_account_id',
                    'destination_document'      => 'required_without_all:destination_email,destination_account_id',
                    'destination_account_id'    => 'required_without_all:destination_email,destination_document',

                    'value' => 'required|numeric',
                ];
                break;
            case "transaction.deposit":
                $this->rules = [
                    'destination_email'         => 'required_without_all:destination_document,destination_account_id',
                    'destination_document'      => 'required_without_all:destination_email,destination_account_id',
                    'destination_account_id'    => 'required_without_all:destination_email,destination_document',

                    'value' => 'required|numeric',
                ];
                break;
        }

        return $this->rules;
    }
}
