<?php

namespace App\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListDataRequest extends FormRequest
{
    public string $_order_by = "created_at";

    public string $_sort = "ASC";

    public function rules()
    {
        return [
            'order_by' => ['string'],
            'sort' => ['string', 'regex:(ASC|DESC)']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'order_by' => ($this->has('order_by')) ? $this->get('order_by') : $this->_order_by,
            'sort' => ($this->has('sort')) ? $this->get('sort') : $this->_sort
        ]);
    }
}
