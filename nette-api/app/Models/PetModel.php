<?php

namespace App\Models;

use App\Models\Pet;
use SimpleXMLElement;

/**
 * Model to work with pets
 */
class PetModel
{
    /**
     * path to config file
     * @var string
     */
    private $configFile = './../config/config.json';

    /**
     * Config data
     * @var string
     */
    private $config = null;

    /**
     * name of entity - "pets"
     * @var string
     */
    protected $entityName = null;

    /**
     * name of entity item - pet
     * @var string
     */
    protected $entityItemName = null;

    public $uploadDir = null;

    /**
     * @param string $filePathXML - file path to "database" of pets
     */
    public function __construct(private string $filePathXML)
    {
        $this->loadConfig();
    }
    /**
     * function returns list of ALL pets
     * @return array
     */
    public function getPets(): array
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
     * load configuration data
     * @return void
     */
    private function loadConfig(): void
    {
        $confFileContent = file_get_contents($this->configFile);
        $this->config = json_decode($confFileContent);
        $this->entityName = $this->config->entityName;
        $this->entityItemName = $this->config->entityItemName;
        $this->uploadDir = $this->config->uploadDir;
    }
    /**
     * Summary of saveConfig
     * @return void
     */
    private function saveConfig(): void
    {
        file_put_contents($this->configFile, json_encode($this->config));
    }
    /**
     * function return pets by selected status
     * @param string $status
     * @return array
     */
    public function getPetsByStatus(string $status): array
    {
        $pets = $this->getPets();
        $petsArray = [];

        foreach ($pets as $pet) {
            if ($pet['status'] === $status) {
                $petsArray[] = $pet;
            }
        }
        return $petsArray;
    }
    /**
     * function returns used pet's ID
     * @return mixed
     */
    public function getLastID(): string
    {
        return $this->config->lastId;
    }
    /**
     *function sets new last ID
     * @param mixed $newLastID
     * @return void
     */
    public function setLastID(mixed $newLastID): void
    {
        $this->config->lastId = $newLastID;
        $this->saveConfig();
    }
    /**
     * function returns pet with all attributes in array by his ID
     * @param string $id
     * @return array
     */
    public function getPet(string $id): array
    {

        $petByID = null;
        $pets = $this->getPets();

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
    public function createPet(array $petData): void
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
     * function adds pet element to xml
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
     * function deletes pet
     * @param string $id
     * @return void
     */
    public function deletePet(string $id): void
    {
        $xml = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getPets();

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
    public function updatePet(array $petData): void
    {
        $id = $petData['id'];
        $xml = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getPets();

        foreach ($existingPets as $pet) {
            if ($pet['id'] == $id) {
                $pet = $petData;
            }
            $this->addPetElement($xml, $pet);
        }

        $xml->asXML($this->filePathXML);
    }

}


