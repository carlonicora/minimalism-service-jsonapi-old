<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use carlonicora\minimalism\services\jsonapi\resources\resourceIdentifierObject;
use JsonException;
use PHPUnit\Framework\TestCase;
use tests\traits\arrayTraitTest;
use tests\traits\traitTest;

class resourceIdentifierObjectTest extends TestCase
{
    use traitTest;
    use arrayTraitTest;

    public function testValidateResourceIdentifierObjectCreation() : resourceIdentifierObject
    {
        $object = $this->getResourceIdentifierObjectHelper()->generateResourceIdentifierObjectMinimal();
        $this->assertInstanceOf(transformationInterface::class, $object);
        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('user', $array['type']);

        return $object;
    }

    public function testValidateResourceIdentifierObjectCreationWithoutId() : void
    {
        $object = $this->getResourceIdentifierObjectHelper()->generateResourceIdentifierObjectMinimalWithoutId();
        $this->assertInstanceOf(transformationInterface::class, $object);
        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertNull($array['id']);
        $this->assertEquals('user', $array['type']);
    }

    public function testValidateResourceIdentifierObjectCreationWithMeta() : void
    {
        $object = $this->getResourceIdentifierObjectHelper()->generateResourceIdentifierObjectWithMeta();
        $this->assertInstanceOf(transformationInterface::class, $object);
        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['meta']['metaOne']);
    }

    /**
     * @depends testValidateResourceIdentifierObjectCreation
     * @param resourceIdentifierObject $object
     */
    public function testCorrectMetaAddedOneAtTheTime(resourceIdentifierObject $object) : void
    {
        $object->addMeta('one', 1);
        $object->addMeta('two', 2);

        $array = $object->toArray();

        $this->assertEquals(1, $array['meta']['one']);
        $this->assertEquals(2, $array['meta']['two']);
    }

    /**
     * @depends testValidateResourceIdentifierObjectCreation
     * @param resourceIdentifierObject $object
     */
    public function testCorrectMetaAddedAtTheSameTime(resourceIdentifierObject $object) : void
    {
        $object->addMetas($this->arrayMeta);

        $array = $object->toArray();

        $this->assertEquals(1, $array['meta']['metaOne']);
        $this->assertEquals(2, $array['meta']['metaTwo']);
    }

    /**
     * @depends testValidateResourceIdentifierObjectCreation
     * @param resourceIdentifierObject $object
     * @throws JsonException
     * @throws JsonException
     */
    public function testCorrectJsonCreation(resourceIdentifierObject $object) : void
    {
        $array = $object->toArray();
        $json = json_encode($array, JSON_THROW_ON_ERROR, 512);

        $this->assertEquals($json, $object->toJson());
    }
}