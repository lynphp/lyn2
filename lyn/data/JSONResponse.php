<?php

namespace lyn\data;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JSONResponse
{
    public $requestTime;
    public $data;
    public $responseCode = 200;
    private function getSerializer()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        return new Serializer($normalizers, $encoders);
    }
    public function toJSONString()
    {
        return $this->getSerializer()->serialize($this, 'json');
    }
}
