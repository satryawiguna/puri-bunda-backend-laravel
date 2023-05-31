<?php

namespace App\Http\Requests\Employee;

use App\Core\Requests\ListSearchPageDataRequest;

class EmployeeListSearchPageDataRequest extends ListSearchPageDataRequest
{
    private int | null $_unit_id = null;

    private int | null $_position_id = null;

    private string | null $_join_date_start = null;

    private string | null $_join_date_end = null;

    public function rules()
    {
        return array_merge([
            'unit_id' => ['int', 'nullable'],
            'position_id' => ['int', 'nullable'],
            'join_date_start' => ['date', 'nullable'],
            'join_date_end' => ['date', 'nullable'],
        ], parent::rules());
    }

    public function prepareForValidation()
    {
        $this->merge([
            'order_by' => ($this->has('order_by')) ? $this->get('order_by') : $this->_order_by,
            'sort' => ($this->has('sort')) ? $this->get('sort') : $this->_sort,
            'search' => ($this->has('search')) ? $this->get('search') : $this->_search,
            'page' => ($this->has('page')) ? $this->get('page') : $this->_page,
            'per_page' => ($this->has('per_page')) ? $this->get('per_page') : $this->_per_page,
            'unit_id' => ($this->has('unit_id')) ? $this->get('unit_id') : $this->_unit_id,
            'position_id' => ($this->has('position_id')) ? $this->get('position_id') : $this->_position_id,
            'join_date_start' => ($this->has('join_date_start')) ? $this->get('join_date_start') : $this->_join_date_start,
            'join_date_end' => ($this->has('join_date_end')) ? $this->get('join_date_end') : $this->_join_date_end,
        ]);
    }
}
