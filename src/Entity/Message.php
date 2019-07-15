<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\employe", inversedBy="id_recepeteur")
     */
    private $id_envoye;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\employe", inversedBy="messages")
     */
    private $id_recepteur;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getIdEnvoye(): ?employe
    {
        return $this->id_envoye;
    }

    public function setIdEnvoye(?employe $id_envoye): self
    {
        $this->id_envoye = $id_envoye;

        return $this;
    }

    public function getIdRecepteur(): ?employe
    {
        return $this->id_recepteur;
    }

    public function setIdRecepteur(?employe $id_recepteur): self
    {
        $this->id_recepteur = $id_recepteur;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
