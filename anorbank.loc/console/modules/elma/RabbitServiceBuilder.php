<?php

namespace console\modules\elma;

use mikemadisonweb\rabbitmq\Configuration;
use console\modules\elma\consumers\ElmaConsumer;

class RabbitServiceBuilder
{
    public static function build()
    {
        $conf = new Configuration();

        $conf->connections = [
            [
                'host'     => 'localhost',
                'port'     => '5672',
                'user'     => 'guest',
                'password' => 'guest',
                'vhost'    => '/',
            ],
        ];

        $conf->exchanges = [];
        $conf->bindings = [];
        $conf->producers = [];

        $conf->queues = [
            [
                'name' => 'elma-to-belt',
            ],
        ];

        $conf->consumers = [
            [
                'name'      => 'elma',
                // Every consumer should define one or more callbacks for corresponding queues
                'callbacks' => [
                    // queue name => callback class name
                    'elma-to-belt' => ElmaConsumer::class,
                ],
            ],
        ];

        return $conf;
    }
}
