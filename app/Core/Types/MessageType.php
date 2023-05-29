<?php

namespace App\Core\Types;

enum MessageType: string
{
    const SUCCESS = "SUCCESS";
    const INFO = "INFO";
    const WARNING = "WARNING";
    const ERROR = "ERROR";
}
