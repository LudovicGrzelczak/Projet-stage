<?php

namespace App\Entity;

use App\Repository\JeuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
 

#[ORM\Entity(repositoryClass: JeuxRepository::class)]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false, hardDelete:true)]

class Jeux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $imageFilename;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    #[ORM\OneToMany(mappedBy: 'jeu_id', targetEntity: Servers::class)]
    private $servers;

    #[ORM\Column(type: 'float')]
    private $base_price;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt): self
    {
        $this->deletedAt = $deletedAt;

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
            $server->setJeuId($this);
        }

        return $this;
    }

    public function removeServer(Servers $server): self
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getJeuId() === $this) {
                $server->setJeuId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
        
    }

    public function getBasePrice(): ?float
    {
        return $this->base_price;
    }

    public function setBasePrice(float $base_price): self
    {
        $this->base_price = $base_price;

        return $this;
    }
 
}
