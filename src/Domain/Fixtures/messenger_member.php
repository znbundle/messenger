<?php

use ZnCore\Db\Fixture\Helpers\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(30);
$fixture->setCallback(function ($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'user_id' => $fixtureFactory->ordIndex($index, 10),
        'chat_id' => $fixtureFactory->ordIndex($index, 20),
    ];
});
return $fixture->generateCollection();
