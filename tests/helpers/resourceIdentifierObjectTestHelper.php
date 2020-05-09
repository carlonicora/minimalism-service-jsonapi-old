<?php
namespace tests\helpers;

use carlonicora\minimalism\services\jsonapi\resources\resourceIdentifierObject;
use tests\traits\arrayTraitTest;

class resourceIdentifierObjectTestHelper {
    use arrayTraitTest;

    /** @var array|string[]  */
    private array $resourceIdentifierObjectMinimal = [
        'type' => 'user',
        'id' => '1'
    ];

    /** @var array  */
    private array $resourceIdentifierObjectWithMeta = [
        'type' => 'user',
        'id' => '1',
        'meta' => [
            'userMetaOne' => 1,
            'userMetaTwo' => 2
        ]
    ];

    /**
     * @return resourceIdentifierObject
     */
    public function generateResourceIdentifierObjectMinimal() : resourceIdentifierObject {
        return new resourceIdentifierObject('user', '1');
    }

    /**
     * @return resourceIdentifierObject
     */
    public function generateResourceIdentifierObjectMinimalWithoutId() : resourceIdentifierObject {
        return new resourceIdentifierObject('user');
    }

    /**
     * @return resourceIdentifierObject
     */
    public function generateResourceIdentifierObjectWithMeta() : resourceIdentifierObject {
        return new resourceIdentifierObject('user', '1', $this->arrayMeta);
    }
}