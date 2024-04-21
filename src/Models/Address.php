<?php

namespace App\Models;

class Address {
    private ?int $id;
    private string $street;
    private string $city;
    private string $postalCode;
    private string $houseNo;
    private string $apartmentNo;

    public function __construct(?int $id, string $street, string $city, string $postalCode, string $houseNo, string $apartmentNo)
    {
        $this->id = $id;
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->houseNo = $houseNo;
        $this->apartmentNo = $apartmentNo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }


    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getHouseNo(): string
    {
        return $this->houseNo;
    }

    public function setHouseNo(string $houseNo): void
    {
        $this->houseNo = $houseNo;
    }

    public function getApartmentNo(): string
    {
        return $this->apartmentNo;
    }

    public function setApartmentNo(string $apartmentNo): void
    {
        $this->apartmentNo = $apartmentNo;
    }


}