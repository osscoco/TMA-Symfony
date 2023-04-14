<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?string $label = null;

    #[ORM\Column]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?int $likes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['getAllUsers', 'getOneUser', 'getAllTweets', 'getOneTweet'])]
    private ?\DateTimeInterface $date_modification = null;

    #[ORM\ManyToOne(inversedBy: 'tweets')]
    #[Groups(['getAllTweets', 'getOneTweet'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
