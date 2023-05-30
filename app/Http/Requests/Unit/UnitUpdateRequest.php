<?php

namespace App\Http\Requests\Unit;

use App\Core\Requests\AuditableRequest;
use App\Helper\Common;
use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'slug' => []
        ];

        return Common::setRuleAuthor($rules, new AuditableRequest());
    }

    public function prepareForValidation()
    {
        Common::setRequestAuthor($this, new AuditableRequest());
    }
}
