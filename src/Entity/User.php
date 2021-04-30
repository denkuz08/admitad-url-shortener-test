<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     * @Groups({"api_me"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @Groups({"api_me"})
     */
    private ?string $login = null;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     * @Groups({"api_me"})
     */
    private ?string $apiToken = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Url", fetch="EXTRA_LAZY", mappedBy="createdUser")
     */
    private Collection $urls;

    public function __construct()
    {
        $this->urls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getUrls(): Collection
    {
        return $this->urls;
    }

    public function addUrl(Url $url): self
    {
        if (!$this->urls->contains($url)) {
            $this->urls->add($url);
        }

        return $this;
    }

    public function getRoles(): array
    {
        return [self::ROLE_USER];
    }

    public function getPassword(): ?string
    {
        return $this->apiToken;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->login;
    }

    public function eraseCredentials(): void
    {
    }
}
