<?php

namespace App\Core\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListDataRequest extends FormRequest
{
    public string $_order_by = "created_at";

    public string $_sort = "ASC";

    public array $_filters = [];

    public array $_relations = [];

    public function rules()
    {
        return [
            'order_by' => ['string'],
            'sort' => ['string', 'regex:(ASC|DESC)'],
            'filters' => ['array'],
            'filters.*' => ['array']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'order_by' => ($this->has('order_by')) ? $this->input('order_by') : $this->_order_by,
            'sort' => ($this->has('sort')) ? $this->input('sort') : $this->_sort,
            'filters' => ($this->has('filters')) ? $this->input('filters') : $this->_filters,
            'relations' => ($this->has('relations')) ? $this->input('relations') : $this->_relations,
        ]);
    }
}
