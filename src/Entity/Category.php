<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AttributeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AttributeRepository::class)]
#[ApiResource]
class Category
{
    /**
     * @param Collection<int, Category> $children
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        #[ORM\GeneratedValue(strategy: 'CUSTOM')]
        #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
        public ?Uuid $id,
        #[ORM\Column(length: 255)]
        public ?string $name,
        #[ORM\Column(length: 255)]
        public ?string $type,
        #[ORM\Column(length: 255)]
        public ?string $className,
        #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
        public Collection $children,
        #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
        public ?self $parent,
    ) {
        $this->children = new ArrayCollection;
    }
}
