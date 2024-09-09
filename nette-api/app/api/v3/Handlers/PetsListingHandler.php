<?php

namespace App\Api\V3\Handlers;

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
        //load pets from xml
        $pets = $this->petModel->getAll();

        //send response
        $response = new JsonApiResponse(Response::S200_OK, $pets);
        return $response;
    }

 }
