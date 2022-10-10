<?php

namespace App\Entity;

use App\Repository\PartenairePermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartenairePermissionRepository::class)]
class PartenairePermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_members_read = null;

    #[ORM\Column]
    private ?bool $is_members_write = null;

    #[ORM\Column]
    private ?bool $is_members_add = null;

    #[ORM\Column]
    private ?bool $is_members_products_add = null;

    #[ORM\Column]
    private ?bool $is_members_payment_schedules_read = null;

    #[ORM\Column]
    private ?bool $is_members_statistiques_read = null;

    #[ORM\Column]
    private ?bool $is_members_subscription_read = null;

    #[ORM\Column]
    private ?bool $is_payment_schedules_read = null;

    #[ORM\Column]
    private ?bool $is_payment_schedules_write = null;

    #[ORM\Column]
    private ?bool $is_payment_day_read = null;

    #[ORM\OneToOne(inversedBy: 'partenairePermission', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partenaire $partenaire = null;

    #[ORM\OneToMany(mappedBy: 'partenaire_permission', targetEntity: StructurePermission::class)]
    private Collection $structure_permission;

    public function __construct()
    {
        $this->structure_permission = new ArrayCollection();
    }






    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsMembersRead(): ?bool
    {
        return $this->is_members_read;
    }

    public function setIsMembersRead(bool $is_members_read): self
    {
        $this->is_members_read = $is_members_read;

        return $this;
    }

    public function isIsMembersWrite(): ?bool
    {
        return $this->is_members_write;
    }

    public function setIsMembersWrite(bool $is_members_write): self
    {
        $this->is_members_write = $is_members_write;

        return $this;
    }

    public function isIsMembersAdd(): ?bool
    {
        return $this->is_members_add;
    }

    public function setIsMembersAdd(bool $is_members_add): self
    {
        $this->is_members_add = $is_members_add;

        return $this;
    }

    public function isIsMembersProductsAdd(): ?bool
    {
        return $this->is_members_products_add;
    }

    public function setIsMembersProductsAdd(bool $is_members_products_add): self
    {
        $this->is_members_products_add = $is_members_products_add;

        return $this;
    }

    public function isIsMembersPaymentSchedulesRead(): ?bool
    {
        return $this->is_members_payment_schedules_read;
    }

    public function setIsMembersPaymentSchedulesRead(bool $is_members_payment_schedules_read): self
    {
        $this->is_members_payment_schedules_read = $is_members_payment_schedules_read;

        return $this;
    }

    public function isIsMembersStatistiquesRead(): ?bool
    {
        return $this->is_members_statistiques_read;
    }

    public function setIsMembersStatistiquesRead(bool $is_members_statistiques_read): self
    {
        $this->is_members_statistiques_read = $is_members_statistiques_read;

        return $this;
    }

    public function isIsMembersSubscriptionRead(): ?bool
    {
        return $this->is_members_subscription_read;
    }

    public function setIsMembersSubscriptionRead(bool $is_members_subscription_read): self
    {
        $this->is_members_subscription_read = $is_members_subscription_read;

        return $this;
    }

    public function isIsPaymentSchedulesRead(): ?bool
    {
        return $this->is_payment_schedules_read;
    }

    public function setIsPaymentSchedulesRead(bool $is_payment_schedules_read): self
    {
        $this->is_payment_schedules_read = $is_payment_schedules_read;

        return $this;
    }

    public function isIsPaymentSchedulesWrite(): ?bool
    {
        return $this->is_payment_schedules_write;
    }

    public function setIsPaymentSchedulesWrite(bool $is_payment_schedules_write): self
    {
        $this->is_payment_schedules_write = $is_payment_schedules_write;

        return $this;
    }

    public function isIsPaymentDayRead(): ?bool
    {
        return $this->is_payment_day_read;
    }

    public function setIsPaymentDayRead(bool $is_payment_day_read): self
    {
        $this->is_payment_day_read = $is_payment_day_read;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection<int, StructurePermission>
     */
    public function getStructurePermission(): Collection
    {
        return $this->structure_permission;
    }

    public function addStructurePermission(StructurePermission $structurePermission): self
    {
        if (!$this->structure_permission->contains($structurePermission)) {
            $this->structure_permission->add($structurePermission);
            $structurePermission->setPartenairePermission($this);
        }

        return $this;
    }

    public function removeStructurePermission(StructurePermission $structurePermission): self
    {
        if ($this->structure_permission->removeElement($structurePermission)) {
            // set the owning side to null (unless already changed)
            if ($structurePermission->getPartenairePermission() === $this) {
                $structurePermission->setPartenairePermission(null);
            }
        }

        return $this;
    }






}
