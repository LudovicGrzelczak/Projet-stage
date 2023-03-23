<?php

namespace App\Entity;

use App\Repository\DurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DurationRepository::class)]
class Duration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 10)]
    private $type_duration;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    #[ORM\Column(type: 'integer')]
    private $ratio;

    #[ORM\OneToMany(mappedBy: 'duration_id', targetEntity: Servers::class)]
    private $servers;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDuration(): ?string
    {
        return $this->type_duration;
    }

    public function setTypeDuration(string $type_duration): self
    {
        $this->type_duration = $type_duration;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getRatio(): ?int
    {
        return $this->ratio;
    }

    public function setRatio(int $ratio): self
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * @return Collection<int, Servers>
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function addServer(Servers $server): self
    {
        if (!$this->servers->contains($server)) {
            $this->servers[] = $server;
            $server->setDurationId($this);
        }

        return $this;
    }

    public function removeServer(Servers $server): self
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getDurationId() === $this) {
                $server->setDurationId(null);
            }
        }

        return $this;
    }
    
}
