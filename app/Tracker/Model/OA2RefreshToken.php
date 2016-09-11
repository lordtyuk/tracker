<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * OA2RefreshToken
 *
 * @ORM\Table(name="OA2RefreshToken")
 * @ORM\Entity(repositoryClass="Tracker\Model\OA2RefreshTokenRepository")
 */
class OA2RefreshToken extends Base\OA2RefreshToken implements RefreshTokenEntityInterface
{
}
