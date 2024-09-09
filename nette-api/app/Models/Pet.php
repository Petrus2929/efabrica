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

    /**
     * Status
     * @var string
     */
    private $status;

    /**
     * ImageName
     * @var string
     */
    private $imageName;

    /**
     * @return string
     */
    public function getId() :string
    {
        return $this->id;
    }
    /**
     * 
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }
    /**
     * 
     * @return string
     */
    public function getCategory() :string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getStatus() :string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getImageName() :string
    {
        return $this->imageName;
    }
    /**
     * create Pet object from array
     * @param array $data
     * @return Pet
     */
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