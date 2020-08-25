<?php

use PhpLab\Eloquent\Fixture\Helpers\FixtureFactoryHelper;

$fixture = new FixtureFactoryHelper;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureFactoryHelper $fixtureFactory) {
    return [
        'id' => $index,
        'text' => 'text ' . $index,
        'author_id' => 11 - $fixtureFactory->ordIndex($index, 10),
    ];
});
return $fixture->generateCollection();
