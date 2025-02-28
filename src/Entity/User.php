<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
#[UniqueEntity(fields: ['pseudo'], message: 'Il existe déjà un compte avec ce nom d\'utilisateur')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 180)]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 50)]
    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[Assert\GreaterThanOrEqual(
        value: '1944-01-01'
    )]
    #[Assert\LessThanOrEqual(
        value: 'today - 7 year',
    )]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $birthAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Assert\Length(min: 5, max: 300)]
    #[Assert\Regex('/^\w+/')]
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

    #[ORM\Column(length: 50, unique: true)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'members')]
    private Collection $activities;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[Assert\Regex('/^\w+/')]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[Assert\NotBlank]
    #[ORM\Column(nullable: true)]
    private ?float $lat = null;

    #[Assert\NotBlank]
    #[ORM\Column(nullable: true)]
    private ?float $lng = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Signalment::class, orphanRemoval: true)]
    private Collection $signalments;

    public function __construct()
    {
        $this->myFriends = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
        $this->trips = new ArrayCollection();
        $this->tripRequests = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->signalments = new ArrayCollection();
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

    public function getBirthAt(): ?\DateTimeImmutable
    {
        return $this->birthAt;
    }

    public function setBirthAt(?\DateTimeImmutable $birthAt): static
    {
        $this->birthAt = $birthAt;

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
    public function getMyFriendUsers(): array
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
    public function getMyBlockedUsers(): array
    {
        $blocked = $this->myFriends->matching(FriendRepository::blockedCriteria());
        $myBlocked = [];
        foreach ($blocked as $f) {
            $myBlocked[] = $f->getFriend();
        }
        return $myBlocked;
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getMyFriends(): Collection
    {
        return $this->myFriends->matching(FriendRepository::friendCriteria());
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getMyBlocked(): Collection
    {
        return $this->myFriends->matching(FriendRepository::blockedCriteria());
    }

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

    // /**
    //  * @return array<int, User>
    //  */
    // public function getFriendsWithMe(): array
    // {
    //     $friends = $this->friendsWithMe->matching(FriendRepository::friendCriteria());
    //     $friendsWithMe = [];
    //     foreach ($friends as $f) {
    //         $friendsWithMe[] = $f->getFriend();
    //     }
    //     return $friendsWithMe;
    // }

    // /**
    //  * @return array<int, User>
    //  */
    // public function getBlockedWithMe(): array
    // {
    //     $blocked = $this->friendsWithMe->matching(FriendRepository::blockedCriteria());
    //     $blockedWithMe = [];
    //     foreach ($blocked as $f) {
    //         $blockedWithMe[] = $f->getFriend();
    //     }
    //     return $blockedWithMe;
    // }

    /**
     * @return Collection<int, Friend>
     */
    public function getFriendsWithMe(): Collection
    {
        return $this->friendsWithMe->matching(FriendRepository::friendCriteria());
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getBlockedWithMe(): Collection
    {
        return $this->friendsWithMe->matching(FriendRepository::blockedCriteria());
    }

    public function addFriendsWithMe(Friend $friendsWithMe): static
    {
        if (!$this->friendsWithMe->contains($friendsWithMe)) {
            $this->friendsWithMe->add($friendsWithMe);
            $friendsWithMe->setFriend($this);
        }

        return $this;
    }

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
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        $this->activities->removeElement($activity);

        return $this;
    }

    public function getAge(): string
    {
        if (!$this->getBirthAt()) {
            return 'xx';
        }
        $age = $this->getBirthAt()->diff(new \DateTimeImmutable());
        return $age->y;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): static
    {
        $this->lng = $lng;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * @return Collection<int, Signalment>
     */
    public function getSignalments(): Collection
    {
        return $this->signalments;
    }

    public function addSignalment(Signalment $signalment): static
    {
        if (!$this->signalments->contains($signalment)) {
            $this->signalments->add($signalment);
            $signalment->setMember($this);
        }

        return $this;
    }

    public function removeSignalment(Signalment $signalment): static
    {
        if ($this->signalments->removeElement($signalment)) {
            // set the owning side to null (unless already changed)
            if ($signalment->getMember() === $this) {
                $signalment->setMember(null);
            }
        }

        return $this;
    }
}
