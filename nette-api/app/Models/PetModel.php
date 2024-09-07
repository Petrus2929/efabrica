<?php

namespace App\Models;

use App\Models\Pet;
use SimpleXMLElement;

class PetModel
{

    private $configFile = './../data/config.json';
    private $config = null;
    protected $entityName = null;
    protected $entityItemName = null;

    public function __construct(private string $filePath)
    {
        $this->loadConfig();
    }
    /**
     * List of Pets
     * @return Pet[]
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
     * load configuration
     * @return void
     */
    private function loadConfig() {
        $confFileContent = file_get_contents($this->configFile);
        $this->config = json_decode($confFileContent);
        $this->entityName = $this->config->entityName;
        $this->entityItemName = $this->config->entityItemName;    
    }
    
    private function saveConfig() {
        file_put_contents($this->configFile, json_encode($this->config));
    }

    /**
     * List of Pets by status
     * @return Pet[]
     */
    public function getPetsByStatus(string $status ): array
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

    public function getLastID()
    {
        return $this->config->lastId;
    }

    public function setLastID($newLastID)
    {
        $this->config->lastId = $newLastID;
        $this->saveConfig();    
    }

    public function getPet($id)
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

    public function createPet(array $petData)
    {
        $this->setLastID($this->getLastID()+1);

        $petData['id'] = $this->getLastID();
        
        //load existing xml file with pets
        $xml = simplexml_load_file($this->filePath);

        $this->addPet($xml, $petData);

        //save updated xml file with new pet
        $xml->asXML($this->filePath);

        return $petData;

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

    public function deletePet($id)
    {
        $xml2 = new SimpleXMLElement('<'.$this->entityName.'></'.$this->entityName.'>');

        $existingPets = $this->getPets();

        // Nájdi element, ktorý zodpovedá danému ID a zmaž ho
        foreach ($existingPets as $pet) {
            if ($pet['id'] !== $id) {
                $this->addPet($xml2, $pet);
            }
        }

        // Ulož aktualizovaný XML súbor
        $xml2->asXML($this->filePath);

        return $this->getPets();
    }


    public function updatePet(array $petData)
    {
        $id = $petData['id'];
        $xml2 = new SimpleXMLElement('<'.$this->entityName.'></'.$this->entityName.'>');

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


