<?php

namespace PersiLiao\Base64Codec\Exceptions;

class NotBase64Encoding extends CodingFailedException
{
    public static function create($message = '', $code = 0, $previous = null)
    {
        return new self($message ?: 'Data is not in base64 encoding.', $code, $previous);
    }
}
