<?php

namespace Steelbot\Protocol;

interface IncomingPayloadInterface
{
    const TYPE_TEXT = 'text';
    const TYPE_LOCATION = 'location';

    /**
     * Payload type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * String representation of the payload.
     *
     * @return string
     */
    public function __toString(): string;
}