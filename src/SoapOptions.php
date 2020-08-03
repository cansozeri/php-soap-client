<?php

namespace Canszr\SoapClient;

use Canszr\SoapClient\Exception\UnexpectedConfigurationException;
use Canszr\SoapClient\TypeConverter\TypeConverterCollection;

class SoapOptions
{
    /**
     * @var string
     */
    private $wsdl;

    /**
     * @var array
     */
    private $options;

    public function __construct(string $wsdl, array $options = [])
    {
        $this->wsdl = $wsdl;
        $this->options = $options;
    }

    public static function defaults(string $wsdl, array $options = []): self
    {
        return new self(
            $wsdl,
            array_merge(
                [
                    'trace' => true,
                    'exceptions' => true,
                    'keep_alive' => false,
                    'cache_wsdl' => WSDL_CACHE_DISK, // Avoid memory cache: this causes SegFaults from time to time.
                    'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                    'typemap' => new TypeConverterCollection([
                        new TypeConverter\DateTimeTypeConverter(),
                        new TypeConverter\DateTypeConverter(),
                        new TypeConverter\DecimalTypeConverter(),
                        new TypeConverter\DoubleTypeConverter()
                    ]),
                ],
                $options
            )
        );
    }

    public function getWsdl(): string
    {
        return $this->wsdl;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getTypeMap(): TypeConverterCollection
    {
        return $this->fetchOptionOfTypeWithDefault(
            'typemap',
            TypeConverterCollection::class,
            new TypeConverterCollection()
        );
    }

    public function withTypeMap(TypeConverterCollection $typeConverterCollection): self
    {
        $this->options['typemap'] = $typeConverterCollection;

        return $this;
    }

    public function disableWsdlCache(): self
    {
        $this->options['cache_wsdl'] = WSDL_CACHE_NONE;

        return $this;
    }

    private function fetchOptionOfTypeWithDefault(string $key, string $type, $default)
    {
        $this->options[$key] = $this->options[$key] ?? $default;

        if (!$this->options[$key] instanceof $type) {
            throw UnexpectedConfigurationException::expectedTypeButGot($key, $type, $this->options[$key]);
        }

        return $this->options[$key];
    }

}