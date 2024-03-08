<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity\Basic;

use App\Repository\Basic\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        #[
            ORM\Id,
            ORM\Column(type: UuidType::NAME, unique: true),
            ORM\GeneratedValue(strategy: 'CUSTOM'),
            ORM\CustomIdGenerator(class: 'doctrine.uuid_generator'),
        ]
        public ?Uuid $id = null,
        #[
            ORM\Column(length: 255),
            Assert\NotBlank(message: 'validation.media.originalName.blank'),
            Assert\Length(
                min: 1,
                max: 255,
                minMessage: 'validation.media.originalName.min',
                maxMessage: 'validation.media.originalName.max',
            ),
        ]
        public ?string $originalName = null,
        #[
            ORM\Column(length: 50),
            Assert\NotBlank(message: 'validation.media.extension.blank'),
            Assert\Length(
                min: 1,
                max: 50,
                minMessage: 'validation.media.extension.min',
                maxMessage: 'validation.media.extension.max',
            ),
        ]
        public ?string $extension = null,
        #[
            ORM\Column(length: 180),
            Assert\NotBlank(message: 'validation.media.type.blank'),
        ]
        public ?string $type = null,
        #[
            ORM\Column(length: 255),
            Assert\NotBlank(message: 'validation.media.uploadDir.blank'),
        ]
        public ?string $uploadDir = null,
        #[
            ORM\Column,
            Assert\NotNull(message: 'validation.media.size.null'),
        ]
        public ?int $size = null,
        #[
            ORM\Column,
            Assert\NotNull(message: 'validation.media.public.null'),
        ]
        public ?bool $public = null,
        #[
            ORM\Column,
            Assert\NotNull(message: 'validation.media.generated.null'),
        ]
        public ?bool $generated = null,
        #[ORM\Column]
        public ?bool $uploaded = null,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        public ?string $uploadError = null,
    ) {
    }
}
