<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Application
{
    private Request $request;

    private Response $response;

    private LoaderInterface $loader;

    private RouteCollection $routeCollection;

    /**
     * Application constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->loader = new YamlFileLoader(
            new FileLocator(dirname(__DIR__))
        );

        $this->routeCollection = $this->loader->load('routes.yaml');
    }

    public function run(): void
    {
//        var_dump((new Router(
//            $this->loader, 'routes.yaml'
//        ))->matchRequest($this->request));exit;


        var_dump(
            (new UrlMatcher(
                $this->routeCollection,
                new RequestContext('', $this->request->getRealMethod())
            ))->matchRequest($this->request)
        );
        exit;

        var_dump($this->request->getRealMethod());

        var_dump($this->routeCollection->all());

        exit;
    }
}
