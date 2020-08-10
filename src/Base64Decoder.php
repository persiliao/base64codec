<?php

namespace PersiLiao\Base64Codec;

use PersiLiao\Base64Codec\Exceptions\InvalidFormat;
use PersiLiao\Base64Codec\Exceptions\NotBase64Encoding;
use PersiLiao\Utils\MimeTypeExtensionGuesser;
use function str_replace;
use function strlen;

class Base64Decoder
{
    /**
     * @var string
     */
    private $base64Encoded;

    /**
     * @var array
     */
    private $allowedFormats;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $size = 0;

    public function __construct(string $base64Encoded, array $allowedFormats = [ 'jpeg', 'png', 'gif' ])
    {
        $this->base64Encoded = $base64Encoded;
        $this->allowedFormats = $allowedFormats;

        $this->validate();
    }

    private function validate()
    {
        $parts = explode(',', $this->base64Encoded);
        $format = str_replace([ 'data:', ';', 'base64' ], [ '', '', '' ], $parts[0] ?? '');
        $this->format = (new MimeTypeExtensionGuesser())->guessExtension($format);
        $this->content = $parts[1] ?? '';

        if(!in_array($this->format, $this->allowedFormats, true)){
            throw InvalidFormat::create($this->allowedFormats, $this->format);
        }
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDecodedContent(): string
    {
        $content = base64_decode($this->content);
        if($content === false){
            throw NotBase64Encoding::create();
        }
        $this->size = strlen($content);
        return $content;
    }

    /**
     * @return array
     */
    public function getAllowedFormats(): array
    {
        return $this->allowedFormats;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
