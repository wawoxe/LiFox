<?php

declare(strict_types=1);

/*
 * (c) Mykyta Melnyk <wawoxe@proton.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity\Security;

use function array_unique;

use App\Repository\Security\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a User entity.
 *
 * This class defines the structure and behavior of a user within the application.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(
    fields: ['email'],
    message: 'validation.user.email.exists',
)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    /**
     * @param null|Uuid          $id       the unique database ID based on UUID
     * @param null|string        $email    the email address (identifier)
     * @param array<int, string> $roles    the roles assigned to the user
     * @param null|string        $password the hashed password
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
            ORM\Column(length: 180, unique: true),
            Assert\NotBlank(message: 'validation.user.email.blank'),
            Assert\Email(message: 'validation.user.email.invalid'),
        ]
        public ?string $email = null,
        #[ORM\Column]
        public array $roles = [],
        #[
            ORM\Column,
            Assert\NotBlank(message: 'validation.user.password.blank'),
            Assert\Regex(
                pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,80}$/',
                message: 'validation.user.password.regex',
            ),
        ]
        public ?string $password = null,
    ) {
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Returns the hashed password used to authenticate the user.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Removes sensitive data from the user.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }
}
