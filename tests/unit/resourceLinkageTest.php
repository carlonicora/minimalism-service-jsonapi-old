<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use carlonicora\minimalism\services\jsonapi\resources\resourceLinkage;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use PHPUnit\Framework\TestCase;

class resourceLinkageTest extends TestCase
{
    /** @test */
    public function can_contain_one_resource_identifier_object() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));

        $this->assertNotNull($linkage->resourceObject);
        $this->assertNull($linkage->resourceObjects);
    }

    /** @test */
    public function can_contain_multiple_resource_identifier_objects() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));
        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));

        $this->assertNotNull($linkage->resourceObjects);
        $this->assertNull($linkage->resourceObject);
    }

    /** @test */
    public function generates_single_data() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));

        $linkageArray = $linkage->toArray();

        $this->assertEquals('user', $linkageArray['type']);
        $this->assertEquals(2, $linkageArray['id']);
    }

    /** @test */
    public function generates_array_data() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 1]));
        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));
        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 3]));

        $linkageArray = $linkage->toArray();

        $this->assertEquals('user', $linkageArray[0]['type']);
        $this->assertEquals(1, $linkageArray[0]['id']);
    }

    /** @test */
    public function correct_meta_added_one_at_the_time() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $rio = new resourceObject(['type' => 'user', 'id' => 2]);
        $rio->addMeta('one', 1);
        $rio->addMeta('two', 2);

        $linkage->addResourceObject($rio);

        $linkageArray = $linkage->toArray();

        $this->assertEquals(1, $linkageArray['meta']['one']);
        $this->assertEquals(2, $linkageArray['meta']['two']);
    }

    /** @test */
    public function correct_meta_added_at_the_same_time() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $rio = new resourceObject(['type' => 'user', 'id' => 2]);
        $rio->addMetas(['one' => 1, 'two' => 2]);

        $linkage->addResourceObject($rio);

        $linkageArray = $linkage->toArray();

        $this->assertEquals(1, $linkageArray['meta']['one']);
        $this->assertEquals(2, $linkageArray['meta']['two']);
    }

    /** @test */
    public function correct_meta_added_one_at_the_time_in_one_resource_object_identifier() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $rio = new resourceObject(['type' => 'user', 'id' => 1]);
        $rio->addMeta('one', 1);
        $rio->addMeta('two', 2);

        $linkage->addResourceObject($rio);
        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));

        $linkageArray = $linkage->toArray();

        $this->assertEquals(1, $linkageArray[0]['meta']['one']);
        $this->assertEquals(2, $linkageArray[0]['meta']['two']);
    }

    /** @test */
    public function correct_meta_added_at_the_same_time_in_one_resource_object_identifier() : void
    {
        $linkage = new resourceLinkage();
        $this->assertInstanceOf(transformationInterface::class, $linkage);
        $this->assertInstanceOf(abstractDocumentTransform::class, $linkage);

        $rio = new resourceObject(['type' => 'user', 'id' => 1]);
        $rio->addMetas(['one' => 1, 'two' => 2]);

        $linkage->addResourceObject($rio);
        $linkage->addResourceObject(new resourceObject(['type' => 'user', 'id' => 2]));

        $linkageArray = $linkage->toArray();

        $this->assertEquals(1, $linkageArray[0]['meta']['one']);
        $this->assertEquals(2, $linkageArray[0]['meta']['two']);
    }
}