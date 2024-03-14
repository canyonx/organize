<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TripRequestRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TripRequestRepository::class)]
class TripRequest
{
    public const OWNER = 'OWNER';
    public const PENDING = 'PENDING';
    public const ACCEPTED = 'ACCEPTED';
    public const REFUSED = 'REFUSED';

    public const COLOR = array(
        'OWNER' => 'danger',
        'PENDING' => 'info',
        'ACCEPTED' => 'success',
        'REFUSED' => 'secondary',
    );

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'tripRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\ManyToOne(inversedBy: 'tripRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $member = null;

    #[ORM\OneToMany(mappedBy: 'tripRequest', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

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

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setTripRequest($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTripRequest() === $this) {
                $message->setTripRequest(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this::COLOR[$this->status];
    }

    public function countNewMessage(): int
    {
        // $m = 0;
        // foreach ($this->getMessages() as $message) {

        //     if ($message->isIsRead() == false) {
        //         $m++;
        //     }
        // }
        return $this->getMessages()->count();
    }

    public function __toString(): string
    {
        return $this->getId() . ' - ' . $this->getMember() . ' to ' . $this->getTrip()->getMember() . ', trip ' . $this->getTrip()->getId();
    }
}
