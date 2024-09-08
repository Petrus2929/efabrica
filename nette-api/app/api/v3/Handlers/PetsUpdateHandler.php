<?php

namespace App\Api\V3\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\JsonInputParam;
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
      (new JsonInputParam('arrayOfJsonParsedValues', $schema)),
    ];
  }

  public function handle(array $params): ResponseInterface
  {
    //update pet in xml
    $this->petModel->updatePet($params['arrayOfJsonParsedValues']);

    //send response
    $response = new JsonApiResponse(Response::S200_OK, 'Pet is updated');
    return $response;
  }

}
