<?php

namespace Tests\Architecture\Application;

$queries = 'App\Application\Reports\Queries';
$contracts = 'App\Application\Contracts';
$eloquent = 'App\Infrastructure\Laravel\Eloquent';

expect()->extend(
    'toUseAbstraction',
    function() use ($contracts, $eloquent) {
        expect($this->value)
            ->toUse($contracts)
            ->not->toUse($eloquent);
        return $this;
    }
);

/*
 * The general test does not indicate which specific file fails the test
 * so listing them explicitly here helps identify any offenders.
 * If a new query does not get added to the with() list here it should
 * still get caught by the general Catch-all test.
 */
arch(
    'Listed report queries should depend on abstractions not infrastructure',
    function(string $query) use ($queries) {
        expect("{$queries}\\{$query}")->toUseAbstraction();
    }
)->with([
    'GetActivePositionsQuery',
    'GetAllRealizedGainsQuery',
    'GetExpirablePositionsQuery',
]);

/*
 * Catch-all test in case a new query is created and omitted from the specifics test.
 */
arch(
    'Report queries should depend on abstractions not infrastructure',
    function() use ($queries) {
        expect($queries)->toUseAbstraction();
    }
);
