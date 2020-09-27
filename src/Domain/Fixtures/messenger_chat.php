<?php

use ZnLib\Fixture\Domain\Libs\FixtureGenerator;

$fixture = new FixtureGenerator;
$fixture->setCount(30);
$fixture->setCallback(function ($index, FixtureGenerator $fixtureFactory) {
    return [
        'id' => $index,
        'type' => 'dialog',
        'title' => 'chat ' . $index,
    ];
});
return $fixture->generateCollection();
