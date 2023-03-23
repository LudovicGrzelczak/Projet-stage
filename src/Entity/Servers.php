<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Repository\ServersRepository;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ServersRepository::class)]
#[Gedmo\SoftDeleteable(fieldName:"deletedAt", timeAware:false, hardDelete:true)]
class Servers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'servers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\ManyToOne(targetEntity: Jeux::class, inversedBy: 'servers')]
    #[ORM\JoinColumn(nullable: false)]
    private $jeu_id;

    #[ORM\Column(type: 'datetime_immutable', options:['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedAt;

    #[ORM\ManyToOne(targetEntity: Duration::class, inversedBy: 'servers')]
    #[ORM\JoinColumn(nullable: false)]
    private $duration_id;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?Users
    {
        return $this->user_id;
    }

    public function setUserId(?Users $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getJeuId(): ?Jeux
    {
        return $this->jeu_id;
    }

    public function setJeuId(?Jeux $jeu_id): self
    {
        $this->jeu_id = $jeu_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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
    public function __toString()
    {
        
        return $this->jeu_id;
    }

    public function getDurationId(): ?Duration
    {
        return $this->duration_id;
    }

    public function setDurationId(?Duration $duration_id): self
    {
        $this->duration_id = $duration_id;

        return $this;
    }
    

}
