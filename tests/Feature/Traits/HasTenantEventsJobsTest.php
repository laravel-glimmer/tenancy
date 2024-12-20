<?php

use Glimmer\Tenancy\Jobs\TenantEvents\CreateDatabase;
use Glimmer\Tenancy\Jobs\TenantEvents\MigrateDatabase;
use Glimmer\Tenancy\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Config::set('multitenancy.tenant_events', [
        'created' => [
            CreateDatabase::class,
            MigrateDatabase::class,
            'catch' => function () {
                //
            },
        ],
    ]);

    $this->eventName = 'eloquent.created: Glimmer\Tenancy\Models\Tenant';
    $this->tenant = Tenant::factory()->make();
});

it('has `created` event with it\'s chain jobs', function () {
    $dispatcher = Tenant::getEventDispatcher();

    expect($dispatcher->hasListeners($this->eventName))->toBeTrue();

    $eventReflection = new ReflectionFunction($dispatcher->getListeners($this->eventName)[0]);
    $listenerReflection = new ReflectionFunction($eventReflection->getStaticVariables()['listener']);

    expect($listenerReflection->getStaticVariables()['jobs']->search(CreateDatabase::class))->not->toBeFalse();
});

it('dispatches `created` event', function () {
    Event::fake();

    $this->tenant->save();

    Event::assertDispatched($this->eventName);
});

it('`created` event dispatches jobs chain', function () {
    Queue::fake();

    $this->tenant->save();

    Queue::assertPushedWithChain(CreateDatabase::class, [MigrateDatabase::class]);
});

it('has `catch` in the jobs chain', function () {
    Queue::fake();

    $this->tenant->save();

    expect(count(Queue::pushed(CreateDatabase::class)->get(0)->chainCatchCallbacks))->toBe(1);
});
