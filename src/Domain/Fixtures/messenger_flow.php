<?php

use ZnDatabase\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'message_id' => $index,
        'chat_id' => $fixtureFactory->ordIndex($index, 30),
        'user_id' => $fixtureFactory->ordIndex($index, 5),
        'is_seen' => boolval($index % 10),
    ];
});

return [
    'deps' => [
        'messenger_message',
        'messenger_chat',
        'user_identity',
    ],
    'collection' => $fixture->generateCollection(),
];