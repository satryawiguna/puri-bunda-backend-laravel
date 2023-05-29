<?php

namespace App\Core\Types;


enum LogLevelType: string
{
    const FATAL = "FATAL";
    const ERROR = "ERROR";
    const WARNING = "WARNING";
    const INFO = "INFO";
    const DEBUG = "DEBUG";
    const TRACE = "TRACE";
}
