<?php

namespace App\Api\V3\Handlers;

use Nette\Http\FileUpload;
use Nette\Http\Response;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Params\FileInputParam;
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
      (new FileInputParam('file')),
      (new PostInputParam('name'))->setRequired(),
      (new PostInputParam('category'))->setRequired(),
      (new PostInputParam('status'))->setRequired(),
    ];
  }

  public function handle(array $params): ResponseInterface
  {
     $image = new FileUpload($params['file']);

     $params['imageName'] = '';
     if ($image && $image->isOk() && $image->isImage()) {

      //file path to upload dir
      $uploadDir = $this->petModel->uploadDir;
      $fileName = uniqid('pet'). '.'. $image->getImageFileExtension();

      $filePath = $uploadDir . '/' . $fileName;

      //move file 
      $image->move($filePath);
      $params['imageName'] = $fileName;
    }

     //create new pet in xml
     $this->petModel->create($params);

    //send response
    $response = new JsonApiResponse(Response::S200_OK, 'Pet is created');
    return $response;
  }

}
