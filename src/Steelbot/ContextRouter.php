<?php

namespace Steelbot;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Steelbot\Context\ContextInterface;
use Steelbot\Context\IncludeFileContext;
use Steelbot\Context\PcreRouteMatcher;
use Steelbot\Exception\ContextNotFoundException;
use Steelbot\Protocol\TextMessageInterface;
use Steelbot\Protocol\IncomingPayloadInterface;
use Steelbot\Context\RouteMatcherInterface;
use Steelbot\Protocol\Telegram\HelpContext;

/**
 * Class ContextRouter
 *
 * @package Steelbot
 */
class ContextRouter implements LoggerAwareInterface
{
    /**
     * @var \Steelbot\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var ContextInterface[]
     */
    protected $clientContexts = [];

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \Steelbot\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->setRoute('~^/help$~i', HelpContext::class);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param IncomingPayloadInterface $payload
     *
     * @return \Generator
     */
    public function handle(IncomingPayloadInterface $payload): \Generator
    {
        $client = $payload->getFrom();
        $clientId = $client->getId();

        $this->logger->debug("New payload from $clientId");

        if (isset($this->clientContexts[$clientId])) {
            $context = $this->clientContexts[$clientId];
        } else {
            $context = $this->findContext($payload, $client);

            if ($context === null) {
                throw new ContextNotFoundException;
            }

            $this->logger->debug("Assigning context ".get_class($context)." for $clientId");

            $this->clientContexts[$clientId] = $context;
        }

        if (is_callable($context))  {
            yield $context($payload, $client, $this->app);
            $isResolved = true;

        } elseif ($context instanceof ContextInterface) {
            yield $context->handle($payload);
            $isResolved = $context->isResolved();
        }

        if ($isResolved) {
            $this->logger->debug("Destroying context for $clientId");
            unset($context);
            unset($this->clientContexts[$clientId]);
        }

        return true;
    }

    /**
     * @param RouteMatcherInterface|string $regexp
     * @param string|callable $handlerString
     */
    public function setRoute($matcher, $handler, array $help = []): self
    {
        if (is_string($matcher)) {
            $matcher = new PcreRouteMatcher($matcher);
            $matcher->setHelp($help);
        }

        $this->routes[$matcher->getPriority()][] = [$matcher, $handler];
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
     * @param \Steelbot\string $text
     * @param \Steelbot\ClientInterface $client
     *
     * @return null|ContextInterface
     */
    protected function findContext(IncomingPayloadInterface $payload, ClientInterface $client)
    {
        foreach ($this->routes as $priority => $pairs) {
            foreach ($pairs as $pair) {
                list($routeMatcher, $handler) = $pair;
                $this->logger->debug("Checking route priority $priority", []);

                if ($routeMatcher->match($payload)) {
                    if (is_callable($handler)) {
                        return $handler;
                    } elseif (class_exists($handler, true)) {
                        return new $handler($this->app, $client);
                    } elseif (file_exists($handler)) {
                        return new IncludeFileContext($this->app, $client, $handler);
                    } else {
                        throw new \UnexpectedValueException("Error resolving context: $handler");
                    }
                }
            }
        }

        return null;
    }
}