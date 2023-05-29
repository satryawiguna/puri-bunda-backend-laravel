<?php

namespace App\Core\Requests;

class IdentityableRequest extends AuditableRequest
{
    public int|string $id;
}
