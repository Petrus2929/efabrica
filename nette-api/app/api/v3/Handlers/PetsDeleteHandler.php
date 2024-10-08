<?php

namespace App\Api\V3\Handlers;

use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\GetInputParam;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;
use App\Models\PetModel;

class PetsDeleteHandler extends BaseHandler
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

        // vymaze zvieratko z XML
        $this->petModel->delete($params['id']);

        // send response
        $response = new JsonApiResponse(Response::S200_OK, 'Pet is deleted');
        return $response;
    }

}
