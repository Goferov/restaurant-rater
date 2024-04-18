<?php

namespace App\Models;

class Review {
    private int $id;
    private int $restaurantId;
    private int $rate;
    private string $review;
    private string $addDate;
    private string $userName;

    public function __construct(int $id, int $restaurantId, int $rate, string $review, string $addDate, string $userName)
    {
        $this->id = $id;
        $this->restaurantId = $restaurantId;
        $this->rate = $rate;
        $this->review = $review;
        $this->addDate = $addDate;
        $this->userName = $userName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRestaurantId(): int
    {
        return $this->restaurantId;
    }

    public function setRestaurantId(int $restaurantId): void
    {
        $this->restaurantId = $restaurantId;
    }

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): void
    {
        $this->rate = $rate;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function setReview(string $review): void
    {
        $this->review = $review;
    }

    public function getAddDate(): string
    {
        return $this->addDate;
    }

    public function setAddDate(string $addDate): void
    {
        $this->addDate = $addDate;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }



}