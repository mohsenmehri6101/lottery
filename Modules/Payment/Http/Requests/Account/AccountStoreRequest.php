<?php

namespace Modules\Payment\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
{
    public function rules(): array
    {
        // todo should be set validation from every column account_number card_number shaba_number
        return [
            'account_number' => 'nullable|unique:accounts,account_number',
            'card_number' => 'nullable|unique:accounts,card_number',
            'shaba_number' => 'nullable|unique:accounts,shaba_number',
            'user_id' => 'nullable|exists:users,id',
            'bank_id' => 'nullable|exists:banks,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => trans('custom.payment.accounts.id'),
            'account_number' => trans('custom.payment.accounts.account_number'),
            'card_number' => trans('custom.payment.accounts.card_number'),
            'shaba_number' => trans('custom.payment.accounts.shaba_number'),
            'user_id' => trans('custom.payment.accounts.user_id'),
            'bank_id' => trans('custom.payment.accounts.bank_id'),
        ];
    }

}
