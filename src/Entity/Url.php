<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="date_index", columns={"created_at"})
 *     }
 * )
 * @UniqueEntity("shortCode", message="This short code already in use")
 */
class Url
{
    public const URL_FIELD_POSSIBLE_PROTOCOLS = ["http", "https", "ftp"];
    public const URL_FIELD_ALLOW_RELATIVE_PROTOCOL = true;
    public const URL_FIELD_ERROR_MSG = "Field 'url' is not a valid url";

    public const SHORT_CODE_FIELD_REGEX = "/^[a-zA-Z0-9]+$/";
    public const SHORT_CODE_FIELD_MAX_LENGTH = 100;
    public const SHORT_CODE_FIELD_REGEX_ERROR_MSG = "Field 'short_code' is not matching regex '/^[a-zA-Z0-9]+$/'";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="created_user_id", referencedColumnName="id")
     */
    private ?User $createdUser = null;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Url(
     *     protocols={self::URL_FIELD_POSSIBLE_PROTOCOLS},
     *     relativeProtocol=self::URL_FIELD_ALLOW_RELATIVE_PROTOCOL,
     *     message=self::URL_FIELD_ERROR_MSG
     * )
     */
    private ?string $url = null;

    /**
     * @ORM\Column(type="string", length=self::SHORT_CODE_FIELD_MAX_LENGTH, nullable=true, unique=true)
     * @Assert\Regex(
     *     self::SHORT_CODE_FIELD_REGEX,
     *     message=self::SHORT_CODE_FIELD_REGEX_ERROR_MSG
     * )
     */
    private ?string $shortCode = null;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedUser(): ?User
    {
        return $this->createdUser;
    }

    public function setCreatedUser(?User $createdUser): self
    {
        $this->createdUser = $createdUser;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getShortCode(): ?string
    {
        return $this->shortCode;
    }

    public function setShortCode(?string $shortCode): self
    {
        $this->shortCode = $shortCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
