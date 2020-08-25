<?php

use PhpLab\Eloquent\Fixture\Helpers\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'content_id' => $index,
        'chat_id' => $fixtureFactory->ordIndex($index, 30),
        'is_seen' => boolval($index % 10),
    ];
});
return $fixture->generateCollection();
