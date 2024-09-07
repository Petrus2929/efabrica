<?php

namespace App\Api\V1\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\JsonInputParam;
use Tomaj\NetteApi\Params\PostInputParam;
use Tomaj\NetteApi\Params\RawInputParam;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;
use App\Models\PetModel;

class PetsUpdateHandler extends BaseHandler
{
    public function __construct(private PetModel $petModel)
    {
        parent::__construct();
    }


    public function params(): array
    {
        $schema = '{
            "type": "object",
            "properties": {
                "id": {
                    "type": "number"
                  },                
              "name": {
                "type": "string"
              },
              "category": {
                "type": "string"
              },
              "status": {
                "type": "string"
              }              
            }
          }';

        return [
            (new JsonInputParam('arrayOfJsonParsedValues',  $schema)),
        ];
    }

    public function handle(array $params): ResponseInterface
    {
        //var_dump($params['xx']);
        // update pet in xml
        $pets = $this->petModel->updatePet($params['arrayOfJsonParsedValues']);

        // Vytvorenie JSON odpovede so statusom 200 OK
        $response = new JsonApiResponse(Response::S200_OK, $this->petModel->getPet($params['arrayOfJsonParsedValues']['id']));
        return $response;
    }

}
