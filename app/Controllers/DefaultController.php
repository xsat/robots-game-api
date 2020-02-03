<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
{
    /**
     * @return Response
     *
     * @throws NotFoundException
     */
    public function index(): Response
    {
        throw new NotFoundException('Page was not found.');
    }

    /**
     * @return Response
     */
    public function options(): Response
    {
        return new Response();
    }
}
