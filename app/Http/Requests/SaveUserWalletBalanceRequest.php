<?php

namespace App\Http\Requests;

use App\Models\UserWalletBalance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserWalletBalanceRequest extends FormRequest
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
            'amount' => 'required|integer|lt:999999999|gt:-999999999',
            'type' => ['required', Rule::in(UserWalletBalance::TYPES)],
        ];
    }
}
