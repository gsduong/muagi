<?php

/*
 * This file is part of Laravel Exceptions.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Exceptions;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Response;
use Laravel\Lumen\Exceptions\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the lumen exception hander class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LumenExceptionHandler extends Handler
{
    use ExceptionHandlerTrait;

    /**
     * The logger instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        NotFoundHttpException::class,
    ];

    /**
     * Create a new exception handler instance.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->config = $container->config->get('exceptions', []);
        $this->container = $container;
        $this->log = $container->make(LoggerInterface::class);
    }

    /**
     * Map exception into an illuminate response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception                                 $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function toIlluminateResponse($response, Exception $e)
    {
        $response = new Response($response->getContent(), $response->getStatusCode(), $response->headers->all());

        $response->exception = $e;

        return $response;
    }
}
