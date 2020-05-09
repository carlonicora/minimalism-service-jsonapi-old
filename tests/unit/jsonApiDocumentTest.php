<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\interfaces\responseInterface;
use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use carlonicora\minimalism\services\jsonapi\jsonApiDocument;
use carlonicora\minimalism\services\jsonapi\resources\errorObject;
use carlonicora\minimalism\services\jsonapi\resources\resourceObject;
use JsonException;
use PHPUnit\Framework\TestCase;

final class jsonApiDocumentTest extends TestCase
{
    /** @test */
    public function can_contain_one_resource_object(): void
    {
        $document = new jsonApiDocument();
        $this->assertInstanceOf(transformationInterface::class, $document);
        $this->assertInstanceOf(responseInterface::class, $document);

        $document->addData(new resourceObject(['type' => 'user', 'id' => 1]));

        $this->assertNotNull($document->data);
        $this->assertNull($document->dataList);
    }

    /** @test */
    public function can_contain_multiple_resource_objects(): void
    {
        $document = new jsonApiDocument();

        $document->addData(new resourceObject(['type' => 'user', 'id' => 1]));
        $document->addData(new resourceObject(['type' => 'user', 'id' => 2]));

        $this->assertNull($document->data);
        $this->assertNotNull($document->dataList);
    }

    /** @test */
    public function can_contain_errors(): void
    {
        $document = new jsonApiDocument();

        $document->addError(new errorObject(responseInterface::HTTP_STATUS_500, 'code', 'detail'));

        $this->assertNotNull($document->errors);
    }

    /** @test */
    public function prioritises_error_over_data(): void
    {
        $document = new jsonApiDocument();

        $document->addError(new errorObject(responseInterface::HTTP_STATUS_500, 'code', 'detail'));
        $document->addData(new resourceObject(['type' => 'user', 'id' => 2]));

        $data = $document->toArray();

        $this->assertNotNull($data['errors'] ?? null);
        $this->assertNull($data['data'] ?? null);
    }

    /** @test */
    public function generates_single_data(): void
    {
        $document = new jsonApiDocument();

        $document->addData(new resourceObject(['type' => 'user', 'id' => 1]));

        $data = $document->toArray();

        $this->assertEquals('user', $data['data']['type']);
        $this->assertEquals(1, $data['data']['id']);
    }

    /** @test */
    public function generates_array_data(): void
    {
        $document = new jsonApiDocument();

        $document->addData(new resourceObject(['type' => 'user', 'id' => 1]));
        $document->addData(new resourceObject(['type' => 'user', 'id' => 2]));

        $data = $document->toArray();

        $this->assertEquals('user', $data['data'][0]['type']);
        $this->assertEquals(1, $data['data'][0]['id']);

        $this->assertEquals('user', $data['data'][1]['type']);
        $this->assertEquals(2, $data['data'][1]['id']);
    }

    /** @test */
    public function correct_meta_added_one_at_the_time(): void
    {
        $document = new jsonApiDocument();
        $document->addMeta('one', 1);
        $document->addMeta('two', 2);

        $documentArray = $document->toArray();

        $this->assertEquals(1, $documentArray['meta']['one']);
        $this->assertEquals(2, $documentArray['meta']['two']);
    }

    /** @test */
    public function correct_meta_added_at_the_same_time(): void
    {
        $document = new jsonApiDocument();
        $document->addMetas(['one' => 1, 'two' => 2]);

        $documentArray = $document->toArray();

        $this->assertEquals(1, $documentArray['meta']['one']);
        $this->assertEquals(2, $documentArray['meta']['two']);
    }

    /** @test */
    public function correct_links_added_one_at_the_time(): void
    {
        $document = new jsonApiDocument();
        $document->addLink('link1', 'link1');
        $document->addLink('link2', 'link2');

        $documentArray = $document->toArray();

        $this->assertEquals('link1', $documentArray['links']['link1']);
        $this->assertEquals('link2', $documentArray['links']['link2']);
    }

    /** @test */
    public function correct_links_added_at_the_same_time(): void
    {
        $document = new jsonApiDocument();
        $document->addLinks(['link1' => 'link1', 'link2' => 'link2']);

        $documentArray = $document->toArray();

        $this->assertEquals('link1', $documentArray['links']['link1']);
        $this->assertEquals('link2', $documentArray['links']['link2']);
    }

    /** @test */
    public function correct_self_link_adde(): void
    {
        $document = new jsonApiDocument();
        $document->addSelfLink('selfLink');

        $documentArray = $document->toArray();

        $this->assertEquals('selfLink', $documentArray['links']['self']);
        $this->assertEquals('selfLink', $document->getLink('self'));
    }

    /** @test */
    public function missing_self_link_adde(): void
    {
        $document = new jsonApiDocument();

        $this->assertNull($document->getLink('self'));
    }

    /** @test */
    public function correct_link_with_meta(): void
    {
        $document = new jsonApiDocument();
        $document->addLink('link1', 'http://link1', ['metaOne' => 'meta1']);

        $documentArray = $document->toArray();

        $this->assertEquals('http://link1', $documentArray['links']['link1']['href']);
        $this->assertEquals('meta1', $documentArray['links']['link1']['meta']['metaOne']);
    }

    /** @test
     * @throws JsonException
     */
    public function correct_json(): void
    {
        $document = new jsonApiDocument();

        $documentArray = $document->toArray();
        $jsonArray = json_encode($documentArray, JSON_THROW_ON_ERROR, 512);

        $this->assertEquals($jsonArray, $document->toJson());
    }

    /** @test */
    public function forced_list(): void
    {
        $originalArray = ['type' => 'user', 'id' => '1'];

        $document = new jsonApiDocument();
        $document->addData(new resourceObject($originalArray));
        $document->forceList();

        $documentArray = $document->toArray();

        $this->assertEquals([$originalArray], $documentArray['data']);
    }

    /** @test */
    public function data_list_added(): void
    {
        $dataList = [
            new resourceObject(['type' => 'user', 'id' => '1']),
            new resourceObject(['type' => 'user', 'id' => '2'])
        ];

        $document = new jsonApiDocument();
        $document->addDataList($dataList);

        $documentArray = $document->toArray();

        $this->assertEquals('1', $documentArray['data'][0]['id']);
    }

    /** @test */
    public function data_list(): void
    {
        $dataList = [
            'data' => [
                [
                    'type' => 'user',
                    'id' => '1'
                ],
                [
                    'type' => 'user',
                    'id' => '2'
                ]
            ]
        ];

        $document = new jsonApiDocument($dataList);

        $documentArray = $document->toArray();

        $this->assertEquals('1', $documentArray['data'][0]['id']);
    }

    /** @test */
    public function merged_data_list(): void
    {
        $dataList = [
            new resourceObject(['type' => 'user', 'id' => '1']),
            new resourceObject(['type' => 'user', 'id' => '2'])
        ];

        $data = [
            'data' => [
                'type' => 'user',
                'id' => '3'
            ]
        ];

        $document = new jsonApiDocument($data);
        $document->addDataList($dataList);

        $documentArray = $document->toArray();

        $this->assertEquals('3', $documentArray['data'][0]['id']);
    }

    /** @test */
    public function full_data_array(): void
    {
        $originalArray = [
            'data' => [
                'type' => 'article',
                'id' => '1',
                'attributes' => [
                    'title' => 'title'
                ],
                'relationships' => [
                    'author' => [
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
            ],
            'included' => [
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
            ]
        ];

        $document = new jsonApiDocument($originalArray);

        $documentArray = $document->toArray();

        $this->assertEquals($originalArray, $documentArray);
    }

    /** @test */
    public function full_data_array_wow(): void
    {
        $originalArray = [
            'data' => [
                'type' => 'article',
                'id' => '1',
                'attributes' => [
                    'title' => 'title'
                ],
                'relationships' => [
                    'author' => [
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
            ],
            'included' => [
                [
                    'type' => 'image',
                    'id' => '102',
                    'attributes' => [
                        'file' => '102.jpg'
                    ]
                ],
                [
                    'type' => 'user',
                    'id' => '10',
                    'attributes' => [
                        'name' => 'carlo'
                    ],
                    'relationships' => [
                        'avatar' => [
                            'data' => [
                                'type' => 'image',
                                'id' => '102'
                            ]
                        ]
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
            ]
        ];

        $document = new jsonApiDocument($originalArray);

        $documentArray = $document->toArray();

        $this->assertEquals($originalArray, $documentArray);
    }

    /** @test */
    public function returns_200(): void
    {
        $document = new jsonApiDocument();

        $this->assertEquals('200', $document->getStatus());
    }

    /** @test
     * @throws JsonException
     * @throws JsonException
     */
    public function retunr_empty_json_when_204_or_205(): void
    {
        $document = new jsonApiDocument();
        $document->status = responseInterface::HTTP_STATUS_204;
        $this->assertEquals('', $document->toJson());

        $document->status = responseInterface::HTTP_STATUS_205;
        $this->assertEquals('', $document->toJson());
    }

    /** @test */
    public function generate_protocol_when_testing() : void
    {
        $this->assertEquals('HTTP/1.1', jsonApiDocument::generateProtocol());
    }

    /** @test */
    public function check_every_return_code(): void
    {
        $document = new jsonApiDocument();
        $this->assertEquals('OK', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_200;
        $this->assertEquals('OK', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_504;
        $this->assertEquals('Gateway Timeout', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_503;
        $this->assertEquals('Service Unavailable', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_502;
        $this->assertEquals('Bad Gateway', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_501;
        $this->assertEquals('Not Implemented', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_500;
        $this->assertEquals('Internal Server Error', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_429;
        $this->assertEquals('Too Many Requests', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_428;
        $this->assertEquals('Precondition Required', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_422;
        $this->assertEquals('Unprocessable Entity', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_415;
        $this->assertEquals('Unsupported Media Type', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_412;
        $this->assertEquals('Precondition Failed', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_411;
        $this->assertEquals('Length Required', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_410;
        $this->assertEquals('Gone', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_409;
        $this->assertEquals('Conflict', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_406;
        $this->assertEquals('Not Acceptable', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_405;
        $this->assertEquals('Method Not Allowed', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_404;
        $this->assertEquals('Not Found', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_403;
        $this->assertEquals('Forbidden', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_401;
        $this->assertEquals('Unauthorized', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_400;
        $this->assertEquals('Bad Request', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_304;
        $this->assertEquals('Not Modified', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_204;
        $this->assertEquals('No Content', $document->generateText());

        $document->status = responseInterface::HTTP_STATUS_201;
        $this->assertEquals('Created', $document->generateText());
    }
}