<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use \DateTime;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cet email !')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['private'])]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Tweet::class)]
    #[Groups(['getAllUsers', 'getOneUser'])]
    private Collection $tweets;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        if (!$this->date_creation) {
            $this->date_creation = $date_creation;
        }
        
        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        if (!$this->date_modification) {
            $this->date_modification = $date_modification;
        }

        return $this;
    }

    /**
     * @return Collection<int, Tweet>
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(Tweet $tweet): self
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->add($tweet);
            $tweet->setUser($this);
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): self
    {
        if ($this->tweets->removeElement($tweet)) {
            // set the owning side to null (unless already changed)
            if ($tweet->getUser() === $this) {
                $tweet->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->firstname . " " . $this->lastname;
    }

    public function __toString()
    {
        return $this->firstname . " " . $this->lastname;
    }
}
