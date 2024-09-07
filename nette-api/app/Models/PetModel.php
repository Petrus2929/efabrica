<?php

namespace App\Models;

use App\Models\Pet;
use SimpleXMLElement;

/**
 * Model to work with pets
 */
class PetModel
{
    private $configFile = './../data/config.json';
    private $config = null;
    protected $entityName = null;
    protected $entityItemName = null;
    
    /**
    * @param string $filePath
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
                'status' => (string) $pet->status
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
    public function getLastID() :string
    {
        return $this->config->lastId;
    }
    /**
     *function sets new last ID
     * @param mixed $newLastID
     * @return void
     */
    public function setLastID(mixed $newLastID):void
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

    public function addPet(&$xml, $petData)
    {

        // Pridanie nového elementu <pet>
        $newPet = $xml->addChild($this->entityItemName);
        $newPet->addChild('id', $petData['id']);  // ID nového zvieratka
        $newPet->addChild('name', $petData['name']);  // Meno nového zvieratka
        $newPet->addChild('category', $petData['category']);  // Kategória (typ) nového zvieratka
        $newPet->addChild('status', $petData['status']);  // Status nového zvieratka


    }

    public function deletePet($id): void
    {
        $xml = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getPets();

        // Nájdi element, ktorý zodpovedá danému ID a zmaž ho
        foreach ($existingPets as $pet) {
            if ($pet['id'] !== $id) {
                $this->addPet($xml, $pet);
            }
        }

        // Ulož aktualizovaný XML súbor
        $xml->asXML($this->filePath);


    }


    public function updatePet(array $petData)
    {
        $id = $petData['id'];
        $xml2 = new SimpleXMLElement('<' . $this->entityName . '></' . $this->entityName . '>');

        $existingPets = $this->getPets();

        // Nájdi element, ktorý zodpovedá danému ID a zmaž ho
        foreach ($existingPets as $pet) {
            if ($pet['id'] == $id) {
                $pet = $petData;
            }
            $this->addPet($xml2, $pet);
        }

        // Ulož aktualizovaný XML súbor
        $xml2->asXML($this->filePath);

        return $this->getPets();
    }

}


