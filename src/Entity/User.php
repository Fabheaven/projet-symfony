<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null; // Valeur par défaut vide au lieu de NULL


    #[ORM\Column(length: 128)]
    private ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(length: 128)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $emploi = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $siteURL = null;

    private ?string $confirmPassword = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }
    
    public function setConfirmPassword(?string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validatePasswordConfirmation(ExecutionContextInterface $context, $payload): void
    {
        if ($this->password !== $this->confirmPassword) {
            $context->buildViolation('Les mots de passe doivent correspondre.')
                ->atPath('confirmPassword')
                ->addViolation();
        }
    }

    // Implémentation des autres méthodes nécessaires à UserInterface

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    // Implémentation de UserInterface
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
        // Si vous stockez des informations sensibles, nettoyez-les ici
    }
}
