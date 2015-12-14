<?php

namespace Steelbot;

use Psr\Log\LoggerInterface;
use Monolog;
use Icicle\{Coroutine, Loop};
use Steelbot\Context\ContextProviderCompilerPass;
use Steelbot\Event\IncomingPayloadEvent;
use Steelbot\Exception\ContextNotFoundException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class Application
 *
 * @package Steelbot
 */
class Application extends Kernel
{
    const ENV_DEV = 'dev';
    const ENV_STAGING = 'staging';
    const ENV_PROD = 'prod';

    protected $loadClassCache = false;

    /**
     * @param string $env
     */
    public function __construct()
    {
        if (!defined('STEELBOT_ENV')) {
            define('STEELBOT_ENV', 'dev');
        }
        parent::__construct(STEELBOT_ENV, STEELBOT_ENV == self::ENV_DEV);
    }

    /**
     * @return bool
     */
    public function registerPayloadHandler(): bool
    {
        $coroutine = Coroutine\wrap(function (IncomingPayloadEvent $event) {
            $payload = $event->getPayload();

            $this->getLogger()->info("Received payload.", [
                'from' => $payload->getFrom()->getId(),
                'content' => (string)$payload
            ]);

            try {
                yield $this->getContextRouter()->handle($payload);
            } catch (ContextNotFoundException $e) {
                $this->getLogger()->debug("Handle not found");

                if (!$payload->isGroupChatMessage()) {
                    yield $this->getProtocol()->send($payload->getFrom(), "Command not found");
                }
            }
        }, []);

        $this->getEventDispatcher()->addListener(IncomingPayloadEvent::class, $coroutine);
        return true;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->container->get('logger');
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->container->get('event_dispatcher');
    }

    /**
     * @return \Steelbot\Protocol\AbstractProtocol
     */
    public function getProtocol(): \Steelbot\Protocol\AbstractProtocol
    {
        return $this->container->get('protocol');
    }

    /**
     * @return \Steelbot\ContextRouter
     */
    public function getContextRouter(): \Steelbot\ContextRouter
    {
        return $this->container->get('context_router');
    }

    /**
     * Start steelbot
     */
    public function run()
    {
        echo "Steelbot 4.0-dev\n\n";

        $this->boot();

        $coroutine = Coroutine\create(function() {
            yield $this->getProtocol()->connect();
        });
        $coroutine->done(null, function (\Exception $e) {
            printf("Exception: %s\n", $e);
        });

        $this->registerPayloadHandler();

        Loop\run();
    }

    /**
     * Stop steelbot
     */
    public function stop()
    {
        if (Loop\isRunning()) {
            Loop\stop();
        }
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
        $loader->load(APP_DIR.'/config.yml');
    }

    public function getCacheDir()
    {
        return APP_DIR.'/cache';
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function buildContainer()
    {
        $container = parent::buildContainer();

        $container->addCompilerPass(new ContextProviderCompilerPass());

        return $container;
    }
}
