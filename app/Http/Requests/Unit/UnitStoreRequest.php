<?php

namespace App\Http\Requests\Unit;

use App\Core\Requests\AuditableRequest;
use App\Helper\Common;
use Illuminate\Foundation\Http\FormRequest;

class UnitStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string']
        ];

        return Common::setRuleAuthor($rules, new AuditableRequest());
    }

    public function prepareForValidation()
    {
        Common::setRequestAuthor($this, new AuditableRequest());
    }
}
