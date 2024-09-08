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

    /**
     * @param string $filePath - "database" of pets
     */
    public function __construct(private string $filePath)
    {
        $this->loadConfig();
    }
    /**
     * function returns list of ALL pets
     * @return array
     */
    public function getPets(): array
    {

        if (!file_exists($this->filePath)) {
            return [];
        }

        $xml = simplexml_load_file($this->filePath);
        $petsArray = [];

        foreach ($xml->children() as $pet) {
            $petsArray[] = [
                'id' => (string) $pet->id,
                'name' => (string) $pet->name,
                'category' => (string) $pet->category,
                'status' => (string) $pet->status,
                'imagePath' => (string) $pet->imagePath
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
        $xml = simplexml_load_file($this->filePath);

        $this->addPet($xml, $petData);

        //save updated xml file with new pet
        $xml->asXML($this->filePath);
    }

    /**
     * function adds pet to xml
     * @param SimpleXMLElement $xml
     * @param array $petData
     * @return void
     */
    public function addPet(SimpleXMLElement &$xml, array $petData): void
    {
        $newPet = $xml->addChild($this->entityItemName);
        $newPet->addChild('id', $petData['id']);
        $newPet->addChild('name', $petData['name']);
        $newPet->addChild('category', $petData['category']);
        $newPet->addChild('status', $petData['status']);

        //if this function is called from create pet - data from form
        if (isset($petData['file'])) {
            $newPet->addChild('imagePath', $petData['file']['full_path']);
        }
         //if this function is called from delete pet - data from xml
        if (isset($petData['imagePath'])) {
            $newPet->addChild('imagePath', $petData['imagePath']);
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
                $this->addPet($xml, $pet);
            }
        }

        //save changeg XML
        $xml->asXML($this->filePath);
    }

    /**
     * function apdates pet
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
            $this->addPet($xml, $pet);
        }

        $xml->asXML($this->filePath);
    }

}


