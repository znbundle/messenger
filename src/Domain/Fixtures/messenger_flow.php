<?php

use ZnCore\Db\Fixture\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'content_id' => $index,
        'chat_id' => $fixtureFactory->ordIndex($index, 30),
        'is_seen' => boolval($index % 10),
    ];
});
return $fixture->generateCollection();
