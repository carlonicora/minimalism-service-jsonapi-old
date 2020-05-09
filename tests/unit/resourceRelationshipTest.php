<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceRelationship;
use PHPUnit\Framework\TestCase;

class resourceRelationshipTest extends TestCase
{
    /** @test */
    public function correct_initialisation() : void
    {
        $object = new resourceRelationship();
        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();

        $this->assertNotNull(array_key_exists('data', $array) || array_key_exists('links', $array) || array_key_exists('meta', $array));
    }

    /** @test */
    public function links_one_by_one() : void
    {
        $object = new resourceRelationship();
        $object->addLink('link1', 'link1');
        $object->addLink('link2', 'link2');

        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertNotNull($array['links'] ?? null);

        $this->assertEquals('link1', $array['links']['link1']);
    }

    /** @test */
    public function links_all_at_the_same_time() : void
    {
        $object = new resourceRelationship();
        $object->addLinks(['link1' => 'link1', 'link2' => 'link2']);

        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertNotNull($array['links'] ?? null);

        $this->assertEquals('link1', $array['links']['link1']);
    }

    /** @test */
    public function metas_one_by_one() : void
    {
        $object = new resourceRelationship();
        $object->addMeta('one', 1);
        $object->addMeta('two', 2);

        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertNotNull($array['meta'] ?? null);

        $this->assertEquals(1, $array['meta']['one']);
        $this->assertEquals(2, $array['meta']['two']);
    }

    /** @test */
    public function correct_initialisation_links_all_at_the_same_time() : void
    {
        $object = new resourceRelationship();
        $object->addMetas(['one' => 1, 'two' => 2]);

        $this->assertInstanceOf(abstractDocumentTransform::class, $object);

        $array = $object->toArray();
        $this->assertNotNull($array['meta'] ?? null);

        $this->assertEquals(1, $array['meta']['one']);
        $this->assertEquals(2, $array['meta']['two']);
    }

    /** @test */
    public function add_identifier_object() : void
    {
        $object = new resourceRelationship();

        $object->addResourceObject(new resourceObject(['type' => 'type', 'id' => 1]));
        $object->addResourceObject(new resourceObject(['type' => 'type', 'id' => 2]));

        $array = $object->toArray();
        $this->assertNotNull($array['data'] ?? null);

        $this->assertEquals('type', $array['data'][0]['type']);
        $this->assertEquals(2, $array['data'][1]['id']);
    }
}