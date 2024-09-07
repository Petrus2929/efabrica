<?php

namespace App\Api\V3\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\JsonInputParam;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;
use App\Models\PetModel;

class PetsCreateHandler extends BaseHandler
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

        /*po zvalidovani json objektu z body requestu  vrati pole v ktorom je pod klucom 'arrayOfJsonParsedValues' 
        pole z rozparsovanych udajov z json_decode(body raw data) */
        return [
            (new JsonInputParam('arrayOfJsonParsedValues', $schema)),
        ];
    }

    public function handle(array $params): ResponseInterface
    {

        // vlozi nove zvieratko do xml
        $this->petModel->createPet($params['arrayOfJsonParsedValues']);

        // Vytvorenie JSON odpovede so statusom 200 OK
        $response = new JsonApiResponse(Response::S200_OK, 'Pet is created');
        return $response;
    }

}
