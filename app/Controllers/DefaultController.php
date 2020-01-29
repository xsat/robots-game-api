<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\HttpNotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
{
    /**
     * @return Response
     *
     * @throws HttpNotFoundException
     */
    public function index(): Response
    {
        throw new HttpNotFoundException('Page was not found.');
    }
}
