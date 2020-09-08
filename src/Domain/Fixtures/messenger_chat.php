<?php

use ZnCore\Db\Fixture\Helpers\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(30);
$fixture->setCallback(function ($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'type' => 'dialog',
        'title' => 'chat ' . $index,
    ];
});
return $fixture->generateCollection();
