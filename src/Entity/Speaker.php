<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeakerRepository")
 */
class Speaker
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $namespeaker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conference", inversedBy="speakers")
     */
    private $conference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamespeaker(): ?string
    {
        return $this->namespeaker;
    }

    public function setNamespeaker(string $namespeaker): self
    {
        $this->namespeaker = $namespeaker;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }
}
