<?php

namespace App\Core\Requests;

class ListSearchPageDataRequest extends ListSearchDataRequest
{
    public int $_page = 1;

    public int $_per_page = 10;

    public function rules()
    {
        return array_merge([
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:2']
        ], parent::rules());
    }

    public function prepareForValidation()
    {
        $this->merge([
            'order_by' => ($this->has('order_by')) ? $this->input('order_by') : $this->_order_by,
            'sort' => ($this->has('sort')) ? $this->input('sort') : $this->_sort,
            'search' => ($this->has('search')) ? $this->input('search') : $this->_search,
            'page' => ($this->has('page')) ? $this->input('page') : $this->_page,
            'per_page' => ($this->has('per_page')) ? $this->input('per_page') : $this->_per_page
        ]);
    }
}
