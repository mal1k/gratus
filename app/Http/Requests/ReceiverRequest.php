<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\Receiver;

class ReceiverRequest extends FormRequest
{

    protected $stopOnFirstFailure = false;

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
        $id = 'receiver[id]';

        return [
            'receiver[id]' => 'integer',
            'receiver[email]' => [
                'required', 'string', 'max:255', 'email',
                Rule::unique('receivers', 'email')->ignore($this->$id)
            ],
            'receiver[first_name]' => ['required', 'string', 'max:255'],
            'receiver[last_name]' => ['required', 'string', 'max:255'],
            'receiver[password]' => [
                'required_without:receiver[id]', 'nullable', 'string', Password::defaults()
            ],
            'receiver[status]' => ['required', Rule::in( Receiver::getClientStatuses() )],

            'bank_account[id]' => 'integer',
            'bank_account[account_number]' => [
                'required_with:bank_account[bank_code]', 'nullable', 'digits_between:1,30'
            ],
            'bank_account[bank_code]' => [
                'required_with:bank_account[account_number]', 'nullable', 'alpha_num'
            ],

            'credit_card[id]' => 'integer',
            'credit_card[expired_date]' => [
                'required_with:credit_card[number],credit_card[secret_code],credit_card[zip_code]', 'nullable', 'regex:#[0-9]{2}/[0-9]{2}#'
            ],
            'credit_card[number]' => [
                'required_with:credit_card[expired_date],credit_card[secret_code],credit_card[zip_code]', 'nullable', 'digits_between:1,20'
            ],
            'credit_card[secret_code]' => [
                'required_with:credit_card[number],credit_card[expired_date],credit_card[zip_code]', 'nullable', 'digits_between:1,4'
            ],
            'credit_card[zip_code]' => [
                'required_with:credit_card[number],credit_card[secret_code],credit_card[expired_date]', 'nullable', 'alpha_dash', 'max:255'
            ],

            'receiver_data[id]' => 'integer',
            'receiver_data[is_kyc_passed]' => ['required', 'boolean'],
            'receiver_data[birth_date]' => ['required', 'date_format:m-d-Y', 'max:255'],
            'receiver_data[phone_number]' => ['required', 'not_regex:#[^\w\s\(\)+-]+#', 'max:255'],
            'receiver_data[postal_code]' => ['required', 'alpha_dash', 'max:255'],
            'receiver_data[ssn]' => ['required', 'digits:4'],
            'receiver_data[address]' => ['required', 'not_regex:#[^\w\s\.\(\),-]+#', 'max:255'],
            'receiver_data[address_2]' => ['required', 'not_regex:#[^\w\s\.\(\),-]+#', 'max:255'],
            'receiver_data[state]' => ['required', 'not_regex:#[^\w\s-]+#', 'max:255'],
            'receiver_data[city]' => ['required', 'not_regex:#[^\w\s-]+#', 'max:255'],
            'receiver_data[country]' => ['required', 'not_regex:#[^\w\s-]+#', 'max:255'],
        ];
    }

    /**
     * @{inheritdoc}
     */
    public function attributes()
    {
        return [
            'receiver[id]' => 'ID',
            'receiver[email]' => 'email',
            'receiver[first_name]' => 'first name',
            'receiver[last_name]' => 'last name',
            'receiver[password]' => 'password',
            'receiver[status]' => 'status',

            'bank_account[id]' => 'ID',
            'bank_account[account_number]' => 'account number',
            'bank_account[bank_code]' => 'bank code',

            'credit_card[id]' => 'ID',
            'credit_card[expired_date]' => 'expired date',
            'credit_card[number]' => 'number',
            'credit_card[secret_code]' => 'secret code',
            'credit_card[zip_code]' => 'zip code',

            'receiver_data[id]' => 'ID',
            'receiver_data[birth_date]' => 'birth date',
            'receiver_data[phone_number]' => 'phone number',
            'receiver_data[postal_code]' => 'postal code',
            'receiver_data[ssn]' => 'ssn',
            'receiver_data[address]' => 'address',
            'receiver_data[address_2]' => 'second address',
            'receiver_data[state]' => 'state',
            'receiver_data[city]' => 'city',
            'receiver_data[country]' => 'country',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'receiver[password].required_without' => 'The :attribute field is required in case of creation',
            'receiver[status].in' => 'The :attribute is incorrect',

            'credit_card[expired_date].required_with' => 'The :attribute is required in case you want to add a credit card',
            'credit_card[number].required_with' => 'The :attribute is required in case you want to add a credit card',
            'credit_card[secret_code].required_with' => 'The :attribute is required in case you want to add a credit card',
            'credit_card[zip_code].required_with' => 'The :attribute is required in case you want to add a credit card',

            'receiver_data[birth_date].date_format' => 'The :attribute does not match the date format',
        ];
    }
}
