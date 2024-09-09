<?php

namespace App\Models;
use Nette\Bridges\CacheLatte\CacheMacro;

/**
 * abstract model to work with entities
 */
abstract class EntityModel
{
    /**
     * path to config file
     * @var string
     */
    const CONFIG_FILE = './../config/config.json';

    /**
     * Config data
     * @var string
     */
    private $config = null;

    /**
     * name of entity 
     * @var string
     */
    protected $entityName = null;

    /**
     * name of entity item 
     * @var string
     */
    protected $entityItemName = null;

    public $uploadDir = null;

    /**
     * JSON schema for update record with API
     * @var mixed
     */
    private $schema = null;

    /**
     * @param string $filePathXML - file path to "database" of entities
     */
    public function __construct(protected string $filePathXML)
    {
        $this->loadConfig();
    }

    /**
     * set schema for API update action
     * @param mixed $schema
     * @return void
     */
    public function setSchema(mixed $schema): void
    {
        $this->schema = $schema;
    }

    /**
     * get schema for API update action
     * @return string
     */
    public function getSchema(): string
    {
        return json_encode($this->schema);
    }

    /**
     * load configuration data
     * @return void
     */
    private function loadConfig(): void
    {
        $confFileContent = file_get_contents(self::CONFIG_FILE);
        $this->config = json_decode($confFileContent);
        $this->entityName = $this->config->entityName;
        $this->entityItemName = $this->config->entityItemName;
        $this->uploadDir = $this->config->uploadDir;
        $this->setSchema($this->config->schema);
    }
    /**
     * save configuration data
     * @return void
     */
    private function saveConfig(): void
    {
        file_put_contents(self::CONFIG_FILE, json_encode($this->config));
    }

    /**
     * function returns last used ID of entity
     * @return mixed
     */
    public function getLastID(): string
    {
        return $this->config->lastId;
    }
    /**
     * function sets new last ID
     * @param mixed $newLastID
     * @return void
     */
    public function setLastID(mixed $newLastID): void
    {
        $this->config->lastId = $newLastID;
        $this->saveConfig();
    }


    /**
     * function returns list of ALL records
     * @return array
     */
    public abstract function getAll(): array;

    /**
     * function returns entity with all attributes in array by his ID
     * @param string $id
     * @return array
     */

    public abstract function get(string $id): array;
    /**
     * function creates a new record
     * @param array $newData
     * @return void
     */
    public abstract function create(array $newData): void;


    /**
     * function deletes pet
     * @param string $id
     * @return void
     */
    public abstract function delete(string $id): void;

    /**
     * function updates pet
     * @param array $petData
     * @return array
     */
    public abstract function update(array $changedData): void;

}


