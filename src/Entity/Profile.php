<?php

namespace App\Entity;

use App\Traits\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfileRepository;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Profile
{
    use TimeStampTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $socials = null;

    #[ORM\OneToOne(mappedBy: 'profil', cascade: ['persist', 'remove'])]
    private ?Users $users = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getSocials(): ?string
    {
        return $this->socials;
    }

    public function setSocials(string $socials): static
    {
        $this->socials = $socials;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        // unset the owning side of the relation if necessary
        if ($users === null && $this->users !== null) {
            $this->users->setProfil(null);
        }

        // set the owning side of the relation if necessary
        if ($users !== null && $users->getProfil() !== $this) {
            $users->setProfil($this);
        }

        $this->users = $users;

        return $this;
    }

    public function __toString(): string
    {
        return $this->socials . ' ' . $this->url;
    }
}
