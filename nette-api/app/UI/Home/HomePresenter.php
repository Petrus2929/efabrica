<?php

declare(strict_types=1);

namespace App\UI\Home;
use Nette\Application\Responses\FileResponse;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{

    public function actionShow($image)
    {
        $filePath = '../data/uploads/' . $image;

        if (file_exists($filePath)) {
            $this->sendResponse(new FileResponse($filePath));
        } else {
            $this->sendResponse(new FileResponse('../data/uploads/' . 'default.jpeg'));
            //$this->error('File not found', 404);
        }
    }
}
