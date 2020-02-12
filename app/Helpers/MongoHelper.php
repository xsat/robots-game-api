<?php

declare(strict_types=1);

namespace App\Helpers;

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Exception\InvalidArgumentException;

/**
 * Class MongoHelper
 */
class MongoHelper
{
    /**
     * @param string|null $id
     *
     * @return ObjectId|null
     */
    public static function objectId(?string $id = null): ?ObjectId
    {
        try {
            $object = new ObjectId($id);
        } catch (InvalidArgumentException $exception) {
            return null;
        }

        return $object;
    }
}
