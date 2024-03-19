<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 50)]
    #[Assert\Regex('/^\w+/')]
    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[Assert\GreaterThanOrEqual(
        value: 'now'
    )]
    #[Assert\LessThanOrEqual(
        value: 'today + 7 day',
    )]
    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    #[Assert\NotBlank]
    #[Assert\Regex('/^\w+/')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 10)]
    private ?float $lat = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 10)]
    private ?float $lng = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $member = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: TripRequest::class, orphanRemoval: true)]
    private Collection $tripRequests;

    #[ORM\Column]
    private ?bool $isAvailable = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[Assert\Regex('/^\w+/')]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $location = null;

    public function __construct()
    {
        $this->tripRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection<int, TripRequest>
     */
    public function getTripRequests(): Collection
    {
        return $this->tripRequests;
    }

    public function addTripRequest(TripRequest $tripRequest): static
    {
        if (!$this->tripRequests->contains($tripRequest)) {
            $this->tripRequests->add($tripRequest);
            $tripRequest->setTrip($this);
        }

        return $this;
    }

    public function removeTripRequest(TripRequest $tripRequest): static
    {
        if ($this->tripRequests->removeElement($tripRequest)) {
            // set the owning side to null (unless already changed)
            if ($tripRequest->getTrip() === $this) {
                $tripRequest->setTrip(null);
            }
        }

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->member;
    }

    /**
     * Array of accepted users
     * Used in : trip info to show participants list
     * @return User[]
     */
    public function getAcceptedUsers(): array
    {
        $acceptedUsers = array();
        foreach ($this->getTripRequests() as $tr) {
            if ($tr->getStatus() == TripRequest::ACCEPTED) {
                $acceptedUsers[] = $tr->getMember();
            }
        }

        return $acceptedUsers;
    }

    public function __toString()
    {
        return ucfirst($this->title);
    }
}
