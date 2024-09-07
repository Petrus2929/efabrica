<?php

namespace App\Api\V1\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;
use App\Models\PetModel;

class PetsListingHandler extends BaseHandler
{
    public function __construct(private PetModel $petModel)
    {
        parent::__construct();
     }

    public function handle(array $params): ResponseInterface
    {
        // Načíta zvieratká z XML
        $pets = $this->petModel->getPets();

        // Vytvorenie JSON odpovede so statusom 200 OK
        $response = new JsonApiResponse(Response::S200_OK, $pets);
        return $response;
    }

 }
