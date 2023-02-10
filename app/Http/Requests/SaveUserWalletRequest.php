<?php

namespace App\Http\Requests;

use App\Models\UserWallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserWalletRequest extends FormRequest
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
            'name' => 'required|max:255',
            'type' => ['required', Rule::in(UserWallet::TYPES)],
        ];
    }

    public function validationData()
    {
        $data = parent::validationData();

        return array_merge($data, [
            'name' => preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data['name']),
        ]);
    }
}
