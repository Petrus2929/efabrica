<?php

namespace App\Models;

use App\Models\Pet;
use SimpleXMLElement;

/**
 * Model to work with pets
 */
class PetModel extends EntityModel
{
    /**
     * function returns list of ALL pets
     * @return array
     */
    public function getAll(): array
    {

        if (!file_exists($this->filePathXML)) {
            return [];
        }

        $xml = simplexml_load_file($this->filePathXML);
        $petsArray = [];

        foreach ($xml->children() as $pet) {
            $petsArray[] = [
                'id' => (string) $pet->id,
                'name' => (string) $pet->name,
                'category' => (string) $pet->category,
                'status' => (string) $pet->status,
                'imageName' => (string) $pet->imageName
            ];
        }

        return $petsArray;
    }

    /**
     * function return pets by selected status
     * @param string $status
     * @return array
     */
    public function getPetsByStatus(string $status): array
    {
        $pets = $this->getAll();
        $petsArray = [];

        foreach ($pets as $pet) {
            if ($pet['status'] === $status) {
                $petsArray[] = $pet;
            }
        }
        return $petsArray;
    }

    /**
     * function returns pet with all attributes in array by his ID
     * @param string $id
     * @return array
     */
    public function get(string $id): array
    {
        $petByID = null;
        $pets = $this->getAll();

        foreach ($pets as $pet) {
            if ($pet['id'] == $id) {
                $petByID = $pet;
            }
        }

        return $petByID;
    }
    /**
     * function creates a new pet
     * @param array $petData
     * @return void
     */
    public function create(array $petData): void
    {
        $this->setLastID($this->getLastID() + 1);

        $petData['id'] = $this->getLastID();

        //load existing xml file with pets
        $xml = simplexml_load_file($this->filePathXML);

        $this->addPetElement($xml, $petData);

        //save updated xml file with new pet
        $xml->asXML($this->filePathXML);
    }

    /**
     * function adds pet element to XML file
     * @param SimpleXMLElement $xml
     * @param array $petData
     * @return void
     */
    public function addPetElement(SimpleXMLElement &$xml, array $petData): void
    {
        $pet = Pet::createFromArray($petData);
        $newPet = $xml->addChild($this->entityItemName);
        $newPet->addChild('id', $pet->getId());
        $newPet->addChild('name', $pet->getName());
        $newPet->addChild('category', $pet->getCategory());
        $newPet->addChild('status', $pet->getStatus());

        $a = $pet->getImageName();
        if (isset($a)) {
            $newPet->addChild('imageName', $pet->getImageName());
        }
    }

    /**
     * function deletes pet from XML file
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $xml = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getAll();

        foreach ($existingPets as $pet) {
            if ($pet['id'] !== $id) {
                $this->addPetElement($xml, $pet);
            } else {
                unlink($this->uploadDir . $pet['imageName']);
            }
        }

        //save changed XML
        $xml->asXML($this->filePathXML);
    }

    /**
     * function updates pet
     * @param array $petData
     * @return array
     */
    public function update(array $petData): void
    {
        $id = $petData['id'];
        $xml = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getAll();

        foreach ($existingPets as $pet) {
            if ($pet['id'] == $id) {
                $pet = $petData;
            }
            $this->addPetElement($xml, $pet);
        }

        $xml->asXML($this->filePathXML);
    }

}


