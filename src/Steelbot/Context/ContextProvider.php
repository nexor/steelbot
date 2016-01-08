<?php

namespace Steelbot\Context;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Steelbot\Application;
use Steelbot\ClientInterface;
use Steelbot\Protocol\IncomingPayloadInterface;
use Steelbot\Route\CallableRouteMatcher;
use Steelbot\Route\PcreRouteMatcher;
use Steelbot\Route\RouteMatcherInterface;

/**
 * Class ContextProvider
 * @package Steelbot\Context
 */
class ContextProvider implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param RouteMatcherInterface|string $regexp
     * @param string|callable $handler
     */
    public function setRoute($matcher, $handler, array $help = []): self
    {
        if (is_string($matcher)) {
            $matcher = new PcreRouteMatcher($matcher);
        } elseif (is_callable($matcher)) {
            $matcher = new CallableRouteMatcher($matcher);
        } elseif (!($matcher instanceof RouteMatcherInterface)) {
            throw new \DomainException("Matcher must implement RouteMatcherInterface or be a string");
        }

        $matcher->setHelp($help);
        $this->routes[] = [$matcher, $handler];
        ksort($this->routes);

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Steelbot\Protocol\IncomingPayloadInterface $payload
     * @param \Steelbot\ClientInterface $client
     *
     * @return \Steelbot\Context\ContextInterface|false
     * @throws \Steelbot\Exception\ContextNotFoundException
     */
    public function findContext(IncomingPayloadInterface $payload, ClientInterface $client, Application $app)
    {
        foreach ($this->routes as list($routeMatcher, $handler)) {
            $this->logger->debug("Checking route", ['class' => get_class($routeMatcher)]);

            if ($routeMatcher->match($payload)) {
                if (is_callable($handler)) {
                    $this->logger->debug("Returning callable handler");

                    return $handler;
                } elseif (class_exists($handler, true)) {
                    $this->logger->debug("Returning class handler");

                    return new $handler($app->getProtocol(), $client);
                } elseif (file_exists($handler)) {
                    $this->logger->debug("Returning anonymous class or closure", [
                        'file' => $handler
                    ]);

                    return $this->createContextFromFile($app, $client, $handler);
                } else {
                    throw new \UnexpectedValueException("Error resolving context: $handler");
                }
            }
        }

        return false;
    }

    /**
     * @param \Steelbot\Application $app
     * @param \Steelbot\ClientInterface $client
     * @param string $filename
     *
     * @return \Steelbot\Context\ContextInterface|\Closure
     */
    protected function createContextFromFile(Application $app, ClientInterface $client, $filename)
    {
        return include $filename;
    }
}
