<?php

declare(strict_types=1);

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\V1\GamesController;
use App\Controllers\V1\PlayersController;
use App\Exceptions\HttpNotFoundException;
use Exception;
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
     * Application constructor.
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->response = new Response();
        $this->loader = new YamlFileLoader(
            new FileLocator(__DIR__ . '/../config')
        );
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
             * @uses PlayersController::create
             * @uses PlayersController::show
             * @uses PlayersController::update
             * @uses GamesController::create
             * @uses GamesController::show
             * @uses GamesController::update
             */
            $controller = explode('::', $arguments['_controller']);
            $controller[0] = new $controller[0]($this->request);
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

        $this->response->send();
    }

    /**
     * @param Throwable $throwable
     */
    private function renderException(Throwable $throwable): void
    {
        $this->response = new JsonResponse(
            [
                'type' => $this->getType($throwable),
                'message' => $throwable->getMessage(),
            ],
            $throwable->getCode() ?: 500
        );
    }

    /**
     * @param Throwable $throwable
     *
     * @return string
     */
    private function getType(Throwable $throwable): string
    {
        $className = get_class($throwable);

        if ($className === HttpNotFoundException::class) {
            return 'not-found';
        }

        return 'internal-server-error';
    }
}
