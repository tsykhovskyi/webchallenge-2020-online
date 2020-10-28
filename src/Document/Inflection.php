<?php
declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document(collection="inflections")
 */
class Inflection
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $base;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $inflected;

    public function getBase(): string
    {
        return $this->base;
    }

    public function getInflected(): string
    {
        return $this->inflected;
    }
}
