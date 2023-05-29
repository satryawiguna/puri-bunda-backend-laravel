<?php

namespace App\Core\Requests;

class AuditableRequest
{
    public string $request_by = "system";

    public function rules()
    {
        return [
            'request_by' => ['string']
        ];
    }
}
