<?php

namespace App\Models;

class Pet
{
    /**
     * ID
     * @var string
     */
    private $id;
    /**
     * Name
     * @var string
     */
    private $name;

    /**
     * Category
     * @var string
     */
    private $category;

    private $status;

    private $imageName;

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

    public function getImageName() :string
    {
        return $this->imageName;
    }

    public static function createFromArray(array $data): self
    {
        $pet = new self();
        $pet->id = $data['id'];
        $pet->name = $data['name'];
        $pet->category = $data['category'];
        $pet->status = $data['status'];
        $pet->imageName = $data['imageName']?? '';

        return $pet;
    }
}