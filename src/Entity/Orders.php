<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrdersRepository")
 */
class Orders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $order_datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $customer_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $customer_fname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customer_lname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $customer_email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $customer_phone;

    /**
     * @ORM\Column(type="text")
     */
    private $customer_street;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     */
    private $customer_postcode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $customer_suburb;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $customer_state;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $distinct_unit_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_units_count;

    /**
     * @ORM\Column(type="float")
     */
    private $subtotal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="float")
     */
    private $total_order_value;

    /**
     * @ORM\Column(type="float")
     */
    private $average_unit_price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $shipping_fee;

    /**
     * @ORM\Column(type="float")
     */
    private $grand_total;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(int $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getOrderDatetime(): ?\DateTimeInterface
    {
        return $this->order_datetime;
    }

    public function setOrderDatetime(\DateTimeInterface $order_datetime): self
    {
        $this->order_datetime = $order_datetime;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getCustomerFname(): ?string
    {
        return $this->customer_fname;
    }

    public function setCustomerFname(string $customer_fname): self
    {
        $this->customer_fname = $customer_fname;

        return $this;
    }

    public function getCustomerLname(): ?string
    {
        return $this->customer_lname;
    }

    public function setCustomerLname(?string $customer_lname): self
    {
        $this->customer_lname = $customer_lname;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(?string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customer_phone;
    }

    public function setCustomerPhone(string $customer_phone): self
    {
        $this->customer_phone = $customer_phone;

        return $this;
    }

    public function getCustomerStreet(): ?string
    {
        return $this->customer_street;
    }

    public function setCustomerStreet(string $customer_street): self
    {
        $this->customer_street = $customer_street;

        return $this;
    }

    public function getCustomerPostcode(): ?string
    {
        return $this->customer_postcode;
    }

    public function setCustomerPostcode(?string $customer_postcode): self
    {
        $this->customer_postcode = $customer_postcode;

        return $this;
    }

    public function getCustomerSuburb(): ?string
    {
        return $this->customer_suburb;
    }

    public function setCustomerSuburb(string $customer_suburb): self
    {
        $this->customer_suburb = $customer_suburb;

        return $this;
    }

    public function getCustomerState(): ?string
    {
        return $this->customer_state;
    }

    public function setCustomerState(string $customer_state): self
    {
        $this->customer_state = $customer_state;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getDistinctUnitCount(): ?int
    {
        return $this->distinct_unit_count;
    }

    public function setDistinctUnitCount(int $distinct_unit_count): self
    {
        $this->distinct_unit_count = $distinct_unit_count;

        return $this;
    }

    public function getTotalUnitsCount(): ?int
    {
        return $this->total_units_count;
    }

    public function setTotalUnitsCount(int $total_units_count): self
    {
        $this->total_units_count = $total_units_count;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTotalOrderValue(): ?float
    {
        return $this->total_order_value;
    }

    public function setTotalOrderValue(float $total_order_value): self
    {
        $this->total_order_value = $total_order_value;

        return $this;
    }

    public function getAverageUnitPrice(): ?float
    {
        return $this->average_unit_price;
    }

    public function setAverageUnitPrice(float $average_unit_price): self
    {
        $this->average_unit_price = $average_unit_price;

        return $this;
    }

    public function getShippingFee(): ?float
    {
        return $this->shipping_fee;
    }

    public function setShippingFee(?float $shipping_fee): self
    {
        $this->shipping_fee = $shipping_fee;

        return $this;
    }

    public function getGrandTotal(): ?float
    {
        return $this->grand_total;
    }

    public function setGrandTotal(float $grand_total): self
    {
        $this->grand_total = $grand_total;

        return $this;
    }
}
