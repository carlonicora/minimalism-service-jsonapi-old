<?php
namespace tests\unit;

use carlonicora\minimalism\services\jsonapi\abstracts\abstractDocumentTransform;
use carlonicora\minimalism\services\jsonapi\interfaces\transformationInterface;
use carlonicora\minimalism\services\jsonapi\resources\errorObject;
use PHPUnit\Framework\TestCase;

class errorObjectTest extends TestCase
{
    /** @test */
    public function correct_minimal_initialisation(): void
    {
        $error = new errorObject('status', 'code');
        $this->assertInstanceOf(abstractDocumentTransform::class, $error);
        $this->assertInstanceOf(transformationInterface::class, $error);

        $this->assertEquals('status', $error->status);
        $this->assertEquals('code', $error->code);
    }

    /** @test */
    public function correct_full_initialisation(): void
    {
        $error = new errorObject('status', 'code', 'detail', 1);
        $this->assertInstanceOf(abstractDocumentTransform::class, $error);
        $this->assertInstanceOf(transformationInterface::class, $error);

        $this->assertEquals('status', $error->status);
        $this->assertEquals('code', $error->code);
        $this->assertEquals('detail', $error->detail);
        $this->assertEquals(1, $error->id);
    }

    /** @test */
    public function generate_array_correctly(): void
    {
        $error = new errorObject('status', 'code', 'detail', 1);
        $errorArray = $error->toArray();

        $array = [
            'status' => 'status',
            'id' => 1,
            'code' => 'code',
            'detail' => 'detail'
        ];

        $this->assertEquals($array, $errorArray);
    }

    /** @test */
    public function correct_meta_added_one_at_the_time() : void
    {
        $error = new errorObject('status', 'code', 'detail', 1);
        $error->addMeta('one', 1);
        $error->addMeta('two', 2);

        $errorArray = $error->toArray();

        $this->assertEquals(1, $errorArray['meta']['one']);
        $this->assertEquals(2, $errorArray['meta']['two']);
    }

    /** @test */
    public function correct_meta_added_at_the_same_time() : void
    {
        $error = new errorObject('status', 'code', 'detail', 1);
        $error->addMetas(['one' => 1, 'two' => 2]);

        $errorArray = $error->toArray();

        $this->assertEquals(1, $errorArray['meta']['one']);
        $this->assertEquals(2, $errorArray['meta']['two']);
    }
}