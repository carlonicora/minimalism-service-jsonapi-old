<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use carlonicora\minimalism\services\jsonapi\traits\modelTrait;
use PHPUnit\Framework\TestCase;

class modelTraitTest extends TestCase
{
    public function testModelTrait(): void
    {
        $array = [
            'type' => 'user',
            'id' => '1',
            'attributes' => [
                'name' => 'carlo'
            ]
        ];

        $object = new resourceObject($array);

        $mock = $this->getMockForTrait(modelTrait::class);

        /** @noinspection PhpUndefinedMethodInspection */
        $this->assertEquals($object, $mock->validateJsonapiParameter($array));
    }
}