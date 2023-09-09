<?php

namespace lyn\data;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JSONResponse
{
    public string $requestTime;
    public string $data;
    public int $responseCode = 200;
    private function getSerializer():Serializer
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        return new Serializer($normalizers, $encoders);
    }
    final public function toJSONString():string
    {
        return $this->getSerializer()->serialize($this, 'json');
    }
}
