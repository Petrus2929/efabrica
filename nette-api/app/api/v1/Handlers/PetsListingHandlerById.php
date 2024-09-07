<?php

namespace App\Api\V1\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\GetInputParam;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;
use App\Models\PetModel;

class PetsListingHandlerById extends BaseHandler
{

    public function __construct(private PetModel $petModel)
    {
        parent::__construct();
    }

    public function params(): array
    {
        return [
            (new GetInputParam('id'))->setRequired(),
        ];
    }

    public function handle(array $params): ResponseInterface
    {

        // loads pets from XML by id
        $pets = $this->petModel->getPet($params['id']);

        // Vytvorenie JSON odpovede so statusom 200 OK
        $response = new JsonApiResponse(Response::S200_OK, $pets);
        return $response;
    }

}
