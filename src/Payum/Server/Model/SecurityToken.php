<?php
namespace Payum\Server\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;
use Payum\Core\Model\Token;

/**
 * @Mongo\Document
 *
 * @ORM\Table
 * @ORM\Entity
 */
class SecurityToken extends Token
{
}