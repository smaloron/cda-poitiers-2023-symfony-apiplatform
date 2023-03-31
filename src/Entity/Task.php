<?php

namespace App\Entity;


use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/taches',
            security: 'is_granted("PUBLIC_ACCESS")'),
        new GET(uriTemplate: '/taches/{id}'),
        new POST(uriTemplate: '/taches'),
        new PUT(uriTemplate: '/taches/{id}'),
        new PATCH(uriTemplate: '/taches/{id}'),
        new DELETE(uriTemplate: '/taches/{id}')
    ],
    paginationItemsPerPage: 10,
    security: 'is_granted("ROLE_USER")'
)]
#[ApiFilter(BooleanFilter::class, properties: ['done'])]
#[ApiFilter(SearchFilter::class,
    properties: ['taskName' => 'partial']
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: "au moins {{ limit }} caractères")]
    private ?string $taskName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]

    private ?bool $done = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]

    private ?\DateTimeInterface $dueAt = null;

    public function __construct()
    {
        // Valeur par défaut à la création d'une tâche
        $this->done = false;
        $this->createdAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->taskName;
    }

    public function setTaskName(string $taskName): self
    {
        $this->taskName = $taskName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getDueAt(): ?\DateTimeInterface
    {
        return $this->dueAt;
    }

    public function setDueAt(?\DateTimeInterface $dueAt): self
    {
        $this->dueAt = $dueAt;

        return $this;
    }


    public function isNew(){
        $now = new \DateTime();
        $interval = $this->createdAt->diff($now);
        return $interval->d <= 2;
    }
}
