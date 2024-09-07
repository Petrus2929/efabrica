<?php

namespace App\Api\V1\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\PostInputParam;
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
        return [
            //(new PostInputParam('id'))->setRequired(),
            (new PostInputParam('name'))->setRequired(),
            (new PostInputParam('category'))->setRequired(),
            (new PostInputParam('status'))->setRequired(),
        ];
    }

    public function handle(array $params): ResponseInterface
    {

        // vlozi nove zvieratko do xml
        $pets = $this->petModel->createPet($params);

        // Vytvorenie JSON odpovede so statusom 200 OK
        $response = new JsonApiResponse(Response::S200_OK, $pets);
        return $response;
    }

}
