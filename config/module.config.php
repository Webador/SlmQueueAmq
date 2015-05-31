<?php

return [
    'service_manager' => [
        'factories' => [
            'SlmQueueAmq\Options\AmqOptions'  => 'SlmQueueAmq\Factory\AmqOptionsFactory',
            'SlmQueueAmq\Service\StompClient' => 'SlmQueueAmq\Factory\StompClientFactory',
            'SlmQueueAmq\Worker\AmqWorker'    => 'SlmQueue\Factory\WorkerFactory'
        ]
    ],

    'controllers' => [
        'factories' => [
            'SlmQueueAmq\Controller\AmqWorkerController' => 'SlmQueueAmq\Factory\AmqWorkerControllerFactory',
        ],
    ],

    'console'   => [
        'router' => [
            'routes' => [
                'slm-queue-amq-worker' => [
                    'type'    => 'Simple',
                    'options' => [
                        'route'    => 'queue amq <queue> [--timeout=]',
                        'defaults' => [
                            'controller' => 'SlmQueueAmq\Controller\AmqWorkerController',
                            'action'     => 'process'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'slm_queue' => [
        'active_mq' => [
            'broker_url' => '',
        ],
        'worker_strategies' => [
            'queues' => [
                'default' => [
                    'SlmQueueAmq\Strategy\SubscribeStrategy',
                ],
            ]
        ],
        'strategy_manager' => [
            'invokables' => [
                'SlmQueueAmq\Strategy\SubscribeStrategy' => 'SlmQueueAmq\Strategy\SubscribeStrategy',
            ],
        ],
    ]
];
