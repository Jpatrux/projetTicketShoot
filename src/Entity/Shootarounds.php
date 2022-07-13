<?php

namespace App\Entity;

use App\Repository\ShootaroundRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShootaroundRepository::class)]
class Shootarounds
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $made;

    #[ORM\Column(type: 'integer')]
    private $attempted;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private $place;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $percentage;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMade(): ?int
    {
        return $this->made;
    }


    public function setMade(int $made): self
    {
        $this->made = $made;

        return $this;
    }

    public function getAttempted(): ?int
    {
        return $this->attempted;
    }

    public function setAttempted(int $attempted): self
    {
        $this->attempted = $attempted;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getPercentage(): ?int
    {
        return $this->percentage;
    }

    public function setPercentage(): self
    {

        $this->percentage = $this->made && $this->attempted ? ($this->made / $this->attempted) * 100 : 0;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}
