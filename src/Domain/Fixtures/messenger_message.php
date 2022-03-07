<?php

use ZnDatabase\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'text' => 'text ' . $index,
        'author_id' => 11 - $fixtureFactory->ordIndex($index, 10),
        'chat_id' => $fixtureFactory->ordIndex($index, 30),
    ];
});

return [
    'deps' => [
        'messenger_chat',
        'user_identity',
    ],
    'collection' => $fixture->generateCollection(),
];