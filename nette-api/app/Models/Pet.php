<?php

namespace App\Models;

class Pet
{

    private $id;
    private $name;

    private $category;

    private $status;

    public function getId() :string
    {
        return $this->id;
    }

    public function getName() :string
    {
        return $this->name;
    }

    public function getCategory() :string
    {
        return $this->category;
    }

    
    public function getStatus() :string
    {
        return $this->status;
    }

    public static function createFromArray(array $data): self
    {
        $pet = new self();
        $pet->id = $data['id'];
        $pet->name = $data['name'];
        $pet->category = $data['category'];
        $pet->status = $data['status'];

        return $pet;
    }



}