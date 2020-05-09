<?php
namespace tests\traits;

use tests\helpers\resourceIdentifierObjectTestHelper;

trait traitTest
{
    /** @var resourceIdentifierObjectTestHelper|null  */
    private ?resourceIdentifierObjectTestHelper $resourceIdentifierObjectHelper=null;

    private function getResourceIdentifierObjectHelper() : resourceIdentifierObjectTestHelper {
        if ($this->resourceIdentifierObjectHelper === null){
            $this->resourceIdentifierObjectHelper = new resourceIdentifierObjectTestHelper();
        }

        return $this->resourceIdentifierObjectHelper;
    }
}