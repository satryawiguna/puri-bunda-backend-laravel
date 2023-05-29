<?php

namespace App\Core\Types;


enum HttpResponseType: int
{
    const SUCCESS = 200;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const UNSUPPORT_MEDIA = 415;
    const INTERNAL_SERVER_ERROR = 500;
}
