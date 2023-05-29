<?php

namespace App\Helper;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\AuditableRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Common
{

    public function __construct()
    {
    }

    public static function setRuleAuthor(array $rules, AuditableRequest $auditableRequest)
    {
        return array_merge($rules, $auditableRequest->rules());
    }

    public static function setRequestAuthor(FormRequest $request, AuditableRequest $auditableRequest): void
    {
        $request->merge(['request_by' => (Auth::user()) ? Auth::user()->username : $auditableRequest->request_by]);
    }

    public static function generateQuery($filter, BaseEntity $model): BaseEntity
    {
        switch ($filter['type']) {
            case "equal":
                if ($filter['value'])
                    $model->where($filter['column_name'], "=", $filter['value']);

                break;

            case "greater_equal":
                if ($filter['value'])
                    $model->where($filter['column_name'], ">=", $filter['value']);

                break;

            case "less_equal":
                if ($filter['value'])
                    $model->where($filter['column_name'], "<=", $filter['value']);

                break;

            case "greater":
                if ($filter['value'])
                    $model->where($filter['column_name'], ">", $filter['value']);

                break;

            case "less":
                if ($filter['value'])
                    $model->where($filter['column_name'], "<", $filter['value']);

                break;

            case "like":
                if ($filter['value'])
                    $model->where($filter['column_name'], "LIKE", $filter['value']);

                break;

            case "between":
                if ($filter['value_start'] && $filter['value_end'])
                    $model->whereBetween($filter['column_name'], [$filter['value_start'], $filter['value_end']]);

                break;
        }

        return $model;
    }
}
