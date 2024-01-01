<?php

namespace App\Entity;

use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $birthAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastConnAt = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Friend::class, orphanRemoval: true)]
    private Collection $myFriends;

    #[ORM\OneToMany(mappedBy: 'friend', targetEntity: Friend::class, orphanRemoval: true)]
    private Collection $friendsWithMe;

    #[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'members')]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Trip::class, orphanRemoval: true)]
    private Collection $trips;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: TripRequest::class, orphanRemoval: true)]
    private Collection $tripRequests;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToOne(mappedBy: 'member', cascade: ['persist', 'remove'])]
    private ?Setting $setting = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->myFriends = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->trips = new ArrayCollection();
        $this->tripRequests = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthAt(): ?\DateTimeImmutable
    {
        return $this->birthAt;
    }

    public function setBirthAt(?\DateTimeImmutable $birthAt): static
    {
        $this->birthAt = $birthAt;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastConnAt(): ?\DateTimeImmutable
    {
        return $this->lastConnAt;
    }

    public function setLastConnAt(\DateTimeImmutable $lastConnAt): static
    {
        $this->lastConnAt = $lastConnAt;

        return $this;
    }

    /**
     * @return array<int, User>
     */
    public function getMyFriends(): array
    {
        $friends = $this->myFriends->matching(FriendRepository::friendCriteria());
        $myFriends = [];
        foreach ($friends as $f) {
            $myFriends[] = $f->getFriend();
        }
        return $myFriends;
    }

    /**
     * @return array<int, User>
     */
    public function getMyBlocked(): array
    {
        $blocked = $this->myFriends->matching(FriendRepository::blockedCriteria());
        $myBlocked = [];
        foreach ($blocked as $f) {
            $myBlocked[] = $f->getFriend();
        }
        return $myBlocked;
    }

    // /**
    //  * @return Collection<int, Friend>
    //  */
    // public function getMyFriends(): Collection
    // {
    //     return $this->myFriends->matching(FriendRepository::blockedCriteria());
    // }

    // /**
    //  * @return Collection<int, Friend>
    //  */
    // public function getMyBlocked(): Collection
    // {
    //     return $this->myFriends->matching(FriendRepository::blockedCriteria());
    // }

    // public function addMyFriend(Friend $myFriend): static
    // {
    //     if (!$this->myFriends->contains($myFriend)) {
    //         $this->myFriends->add($myFriend);
    //         $myFriend->setMember($this);
    //     }

    //     return $this;
    // }

    // public function removeMyFriend(Friend $myFriend): static
    // {
    //     if ($this->myFriends->removeElement($myFriend)) {
    //         // set the owning side to null (unless already changed)
    //         if ($myFriend->getMember() === $this) {
    //             $myFriend->setMember(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return array<int, User>
     */
    public function getFriendsWithMe(): array
    {
        $friends = $this->myFriends->matching(FriendRepository::friendCriteria());
        $friendsWithMe = [];
        foreach ($friends as $f) {
            $friendsWithMe[] = $f->getFriend();
        }
        return $friendsWithMe;
    }

    /**
     * @return array<int, User>
     */
    public function getBlockedWithMe(): array
    {
        $blocked = $this->myFriends->matching(FriendRepository::blockedCriteria());
        $blockedWithMe = [];
        foreach ($blocked as $f) {
            $blockedWithMe[] = $f->getFriend();
        }
        return $blockedWithMe;
    }

    // /**
    //  * @return Collection<int, Friend>
    //  */
    // public function getFriendsWithMe(): Collection
    // {
    //     return $this->friendsWithMe->matching(FriendRepository::friendCriteria());
    // }

    // /**
    //  * @return Collection<int, Friend>
    //  */
    // public function getBlockedWithMe(): Collection
    // {
    //     return $this->friendsWithMe->matching(FriendRepository::blockedCriteria());
    // }

    // public function addFriendsWithMe(Friend $friendsWithMe): static
    // {
    //     if (!$this->friendsWithMe->contains($friendsWithMe)) {
    //         $this->friendsWithMe->add($friendsWithMe);
    //         $friendsWithMe->setFriend($this);
    //     }

    //     return $this;
    // }

    // public function removeFriendsWithMe(Friend $friendsWithMe): static
    // {
    //     if ($this->friendsWithMe->removeElement($friendsWithMe)) {
    //         // set the owning side to null (unless already changed)
    //         if ($friendsWithMe->getFriend() === $this) {
    //             $friendsWithMe->setFriend(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->addMember($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            $activity->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setMember($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getMember() === $this) {
                $trip->setMember(null);
            }
        }

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
            $tripRequest->setMember($this);
        }

        return $this;
    }

    public function removeTripRequest(TripRequest $tripRequest): static
    {
        if ($this->tripRequests->removeElement($tripRequest)) {
            // set the owning side to null (unless already changed)
            if ($tripRequest->getMember() === $this) {
                $tripRequest->setMember(null);
            }
        }

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
            $message->setMember($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMember() === $this) {
                $message->setMember(null);
            }
        }

        return $this;
    }

    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    public function setSetting(Setting $setting): static
    {
        // set the owning side of the relation if necessary
        if ($setting->getMember() !== $this) {
            $setting->setMember($this);
        }

        $this->setting = $setting;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString()
    {
        return ucfirst($this->pseudo);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
