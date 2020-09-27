<?php

use ZnLib\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(30);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'user_id' => $fixtureFactory->ordIndex($index, 10),
        'chat_id' => $fixtureFactory->ordIndex($index, 20),
    ];
});
return $fixture->generateCollection();
