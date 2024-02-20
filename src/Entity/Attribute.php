<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AttributeRepository::class)]
final class Attribute
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        #[ORM\GeneratedValue(strategy: 'CUSTOM')]
        #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
        public ?Uuid $id = null,
        #[ORM\Column(length: 255)]
        public ?string $name = null,
        #[ORM\Column(length: 255)]
        public ?string $type = null,
        #[ORM\Column(length: 255)]
        public ?string $className = null,
        #[ORM\Column]
        public ?bool $isRequired = null,
        #[ORM\Column]
        public ?bool $isUnique = null,
    ) {
    }
}
