<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface", message="format de date invalide")
     * @Assert\GreaterThan("today", message="Cette date n'est plus disponible (ou est deja passee)", groups={"front"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface", message="format de date invalide")
     * @Assert\GreaterThan(propertyPath="startDate", message="Cette date doit etre superieure a la date d'arrivee")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    /**
     * Permet de determiner si une annonce n'est pas deja reservee au cas ou un autre utilisateur ferai une reservation
     */
    public function isBookableDays()
    {
        //On determine d'abord les
        //Jours non disponibles, une reservation a deja ete faite durant ces périodes
        //Fonction presentes dans l'entite Ad
        $notAvailableDays = $this->ad->getNotAvailableDays();

        //Ensuite on determine les
        //Jours susceptibles d'etre demandes par les clients
        $bookingDays = $this->getDays();

        //On converti chaque date du tableau en une chaine de caractère

        $formatDays = function ($day) {
            return $day->format('Y-m-d');
        };
        $notAvailable = array_map($formatDays, $notAvailableDays);
        $possibles = array_map($formatDays, $bookingDays);

        //On teste toute les valeurs possible en regardant si l'une de ses valeurs n'est pas encore reseree
        foreach ($possibles as $possible) {
            if (array_search($possible, $notAvailable) !== false) return false;
        }

        return true;
    }

    /**
     * @return DateTime[] contenant les jours pour lesquels on souhaite faire une reservation
     */
    private function getDays()
    {
        $results = range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24 * 60 * 60
        );

        return array_map(function ($day) {
            return new DateTime(date('Y-m-d', $day));
        }, $results);
    }

    /**
     * Permet de calculer la durée d'un séjour
     */
    public function getDuration()
    {
        return $this->endDate->diff($this->startDate)->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
