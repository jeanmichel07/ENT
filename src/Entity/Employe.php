<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeRepository")
 */
class Employe
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Poste", inversedBy="employes")
     */
    private $poste;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departement", inversedBy="employes")
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="id_envoye")
     */
    private $id_recepeteur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="id_recepteur")
     */
    private $messages;

    public function __construct()
    {
        $this->id_recepeteur = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getNumMatricule(): ?string
    {
        return $this->num_matricule;
    }

    public function setNumMatricule(?string $num_matricule): self
    {
        $this->num_matricule = $num_matricule;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getIdRecepeteur(): Collection
    {
        return $this->id_recepeteur;
    }

    public function addIdRecepeteur(Message $idRecepeteur): self
    {
        if (!$this->id_recepeteur->contains($idRecepeteur)) {
            $this->id_recepeteur[] = $idRecepeteur;
            $idRecepeteur->setIdEnvoye($this);
        }

        return $this;
    }

    public function removeIdRecepeteur(Message $idRecepeteur): self
    {
        if ($this->id_recepeteur->contains($idRecepeteur)) {
            $this->id_recepeteur->removeElement($idRecepeteur);
            // set the owning side to null (unless already changed)
            if ($idRecepeteur->getIdEnvoye() === $this) {
                $idRecepeteur->setIdEnvoye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setIdRecepteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getIdRecepteur() === $this) {
                $message->setIdRecepteur(null);
            }
        }

        return $this;
    }
}
