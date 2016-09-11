<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * OA2Client
 *
 * @ORM\Table(name="OA2Client")
 * @ORM\Entity(repositoryClass="Tracker\Model\OA2ClientRepository")
 */
class OA2Client extends Base\OA2Client implements ClientEntityInterface
{
}
