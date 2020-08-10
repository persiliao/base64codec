<?php

namespace PersiLiao\Base64Codec;

use PersiLiao\Base64Codec\Exceptions\InvalidFormat;
use PersiLiao\Base64Codec\Exceptions\NotBase64Encoding;
use PersiLiao\Utils\MimeTypeExtensionGuesser;
use function base64_decode;
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
     * @var string
     */
    private $decodeContent;

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

    private function validate(): void
    {
        $parts = explode(',', $this->base64Encoded);
        $format = str_replace([ 'data:', ';', 'base64' ], [ '', '', '' ], $parts[0] ?? '');
        $this->format = (new MimeTypeExtensionGuesser())->guessExtension($format);
        $content = $parts[1] ?? '';

        if(empty($content)){
            throw NotBase64Encoding::create();
        }

        if(!in_array($this->format, $this->allowedFormats, true)){
            throw InvalidFormat::create($this->allowedFormats, $this->format);
        }

        $decodeContent = base64_decode($content);
        if($decodeContent === false){
            throw NotBase64Encoding::create();
        }
        $this->content = $content;
        $this->decodeContent = $decodeContent;
        $this->size = strlen($decodeContent);
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
        return $this->decodeContent;
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
