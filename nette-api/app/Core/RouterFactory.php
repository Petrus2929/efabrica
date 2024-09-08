<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router[] = new Route('/api/v<version>/<package>[/<id \d+>]', [
			'presenter' => 'Api:Api',
			'action' => 'default',
			'id' => [
				Route::FILTER_IN => function ($id) {
					$_GET['id'] = $id;
					return $id;
				}
			],
		]);
		$router[] = new Route('/api/v<version>/<package>[/<apiAction>][/<params>]', 'Api:Api:default');
	    $router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
		
		return $router;
	}
}
