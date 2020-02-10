<?php

declare(strict_types=1);

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\V1\AuthController;
use App\Controllers\V1\GameController;
use App\Controllers\V1\PlayerController;
use App\Controllers\V1\RegisterController;
use Exception;
use JsonSerializable;
use MongoDB\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Throwable;

/**
 * Class Application
 */
class Application
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * @var LoaderInterface
     */
    private LoaderInterface $loader;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
        $this->loader = new YamlFileLoader(
            new FileLocator(__DIR__ . '/../config')
        );
        $this->client = new Client('mongodb://mongo:27017');
    }

    public function run(): void
    {
        /**
         * @uses Application::renderException
         */
        $renderException = [$this, 'renderException'];
        $errorHandler = false;
        if ($phpHandler = set_exception_handler($renderException)) {
            restore_exception_handler();
            if (!is_array(
                    $phpHandler
                ) || !$phpHandler[0] instanceof ErrorHandler) {
                $errorHandler = true;
            } elseif ($errorHandler = $phpHandler[0]->setExceptionHandler(
                $renderException
            )) {
                $phpHandler[0]->setExceptionHandler($errorHandler);
            }
        }

        try {
            $arguments = (new UrlMatcher(
                $this->loader->load('routes.yaml'),
                new RequestContext(
                    '',
                    $this->request->getRealMethod()
                )
            ))->matchRequest($this->request);

            /**
             * @todo Redesign this solution
             *
             * @uses DefaultController::index
             * @uses RegisterController::create
             * @uses AuthController::login
             * @uses PlayerController::show
             * @uses PlayerController::update
             * @uses GameController::play
             */
            $controller = explode('::', $arguments['_controller']);
            $controller[0] = new $controller[0](
                $this->request,
                $this->client->selectDatabase(
                    'battle'
                )
            );
            unset($arguments['_controller']);
            unset($arguments['_route']);
            $arguments = array_values($arguments);
            $this->response = $controller(...$arguments);
        } catch (Exception $e) {
            $renderException($e);
        } finally {
            if (!$phpHandler) {
                if (
                    set_exception_handler($renderException) === $renderException
                ) {
                    restore_exception_handler();
                }
                restore_exception_handler();
            } elseif (!$errorHandler) {
                $finalHandler = $phpHandler[0]->setExceptionHandler(null);
                if ($finalHandler !== $renderException) {
                    $phpHandler[0]->setExceptionHandler($finalHandler);
                }
            }
        }

        $this->response->headers->add(
            [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => '*',
                'Access-Control-Allow-Headers' => '*',
            ]
        );

        $this->response->send();
    }

    /**
     * @param Throwable $throwable
     */
    private function renderException(Throwable $throwable): void
    {
        $this->response = new JsonResponse(
            $this->getData($throwable),
            $this->getStatusCode($throwable)
        );
    }

    /**
     * @param Throwable $throwable
     *
     * @return array
     */
    private function getData(Throwable $throwable): array
    {
        if ($throwable instanceof JsonSerializable) {
            return $throwable->jsonSerialize();
        }

        return [
            'type' => 'internal-server-error',
            'message' => $throwable->getMessage(),
            'c' => get_class($throwable),
            't' => $throwable->getTraceAsString(),
        ];
    }

    /**
     * @param Throwable $throwable
     *
     * @return int
     */
    private function getStatusCode(Throwable $throwable): int
    {
        $code = $throwable->getCode();

        if ($code <= 0 || $code > 599) {
            return 500;
        }

        return $code;
    }
}
