<?php

namespace PersiLiao\Base64Codec\Exceptions;

class CannotEncodeToBase64 extends CodingFailedException
{
    public static function create($message = '', $code = 0, $previous = null)
    {
        return new self($message ?: 'Failed encoding in base64.', $code, $previous);
    }
}
