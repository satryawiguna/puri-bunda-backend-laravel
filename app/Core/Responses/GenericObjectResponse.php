<?php

namespace App\Core\Responses;

class GenericObjectResponse extends BasicResponse
{
    public $dto;

    public function getDto()
    {
        return $this->dto ?? new \stdClass();
    }
}
