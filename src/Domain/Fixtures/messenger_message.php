<?php

use ZnLib\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(300);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'text' => 'text ' . $index,
        'author_id' => 11 - $fixtureFactory->ordIndex($index, 10),
    ];
});
return $fixture->generateCollection();
