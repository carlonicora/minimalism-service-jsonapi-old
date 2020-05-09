<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\resources\resourceIdentifierObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use PHPUnit\Framework\TestCase;

class resourceObjectTest extends TestCase
{
    /** @test */
    public function correct_initialisation_minimal() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1]);
        $this->assertInstanceOf(resourceIdentifierObject::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('type', $array['type']);
    }

    /** @test */
    public function correct_initialisation_attributes() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1, 'attributes' => ['attirbute1' => 1]]);
        $this->assertInstanceOf(resourceIdentifierObject::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('type', $array['type']);
    }

    /** @test */
    public function correct_initialisation_meta() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1, 'meta' => ['one' => 1]]);
        $this->assertInstanceOf(resourceIdentifierObject::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('type', $array['type']);
    }

    /** @test */
    public function correct_initialisation_links() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1, 'links' => ['self' => 'https://self']]);
        $this->assertInstanceOf(resourceIdentifierObject::class, $object);

        $array = $object->toArray();
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('type', $array['type']);
    }
    /** @test */
    public function correct_initialisation_full() : void
    {
        $originalArray = [
            'type' => 'article',
            'id' => '1',
            'attributes' => [
                'title' => 'title'
            ],
            'relationships' => [
                'author' =>[
                    'links' => [
                        'self' => 'https://self'
                    ],
                    'meta' => [
                        'one' => 1
                    ],
                    'data' => [
                        'type' => 'user',
                        'id' => '10'
                    ]
                ],
                'images' => [
                    'data' => [
                        ['type' => 'image', 'id' => '100'],
                        ['type' => 'image', 'id' => '101']
                    ]
                ]
            ]
        ];

        $included = [
            [
                'type' => 'user',
                'id' => '10',
                'attributes' => [
                    'name' => 'carlo'
                ]
            ],
            [
                'type' => 'image',
                'id' => '100',
                'attributes' => [
                    'file' => '100.jpg'
                ]
            ],
            [
                'type' => 'image',
                'id' => '101',
                'attributes' => [
                    'file' => '101.jpg'
                ]
            ]
        ];

        $object = new resourceObject($originalArray, $included);

        $this->assertEquals('carlo', $object->getRelationship('author')->data->resourceObject->attributes['name']);

        $this->assertEquals($originalArray, $object->toArray());
    }

    /** @test */
    public function get_single_relationship() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1]);

        $rio = new resourceObject(['type' => 'user', 'id' => 2]);
        $object->addResourceLink($rio);

        $array = $object->getRelationship('user')->toArray();
        $this->assertEquals(2, $array['data']['id']);
    }

    /** @test */
    public function get_null_relationship() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1]);

        $this->assertNull($object->getRelationship('user'));
    }

    /** @test */
    public function get_object_with_single_resource_link() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1]);

        $object->addResourceLink(new resourceObject(['type' => 'user', 'id' => 2]));

        $array = $object->toArray();

        $this->assertEquals(1, $array['id']);
        $this->assertEquals(2, $array['relationships']['user']['data']['id']);
    }

    /** @test */
    public function get_object_with_multiple_resource_link() : void
    {
        $object = new resourceObject(['type' => 'type', 'id' => 1]);

        $object->addResourceLink(new resourceObject(['type' => 'user', 'id' => 2]));
        $object->addResourceLink(new resourceObject(['type' => 'user', 'id' => 3]));

        $array = $object->toArray();

        $this->assertEquals(1, $array['id']);
        $this->assertEquals(2, $array['relationships']['user']['data'][0]['id']);
        $this->assertEquals(3, $array['relationships']['user']['data'][1]['id']);
    }
}