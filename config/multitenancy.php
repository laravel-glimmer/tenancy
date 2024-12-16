<?php

use Glimmer\Tenancy\Actions\MakeQueueMaybeTenantAwareAction;
use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Queue\CallQueuedClosure;
use Spatie\Multitenancy\Actions\ForgetCurrentTenantAction;
use Spatie\Multitenancy\Actions\MakeTenantCurrentAction;
use Spatie\Multitenancy\Actions\MigrateTenantAction;

return [
    /*
     * This class is responsible for determining which tenant should be current for the given request.
     *
     * This class should extend `Spatie\Multitenancy\TenantFinder\TenantFinder`
     *
     * Available tenant finders:
     *  `Glimmer\Tenancy\TenantFinders\DomainTenantFinder`
     *  `Glimmer\Tenancy\TenantFinders\SubDomainTenantFinder`
     *  `Glimmer\Tenancy\TenantFinders\DomainAndSubDomainTenantFinder`
     *  `Glimmer\Tenancy\TenantFinders\PathParameterTenantFinder`
     */
    'tenant_finder' => null,

    /*
     * These fields are used by tenant:artisan command to match one or more tenant.
     */
    'tenant_artisan_search_fields' => [
        'id',
    ],

    /*
     * These tasks will be performed when switching tenants.
     *
     * A valid task is any class that implements Spatie\Multitenancy\Tasks\SwitchTenantTask
     */
    'switch_tenant_tasks' => [
        // \Spatie\Multitenancy\Tasks\PrefixCacheTask::class,
        // \Spatie\Multitenancy\Tasks\SwitchRouteCacheTask::class,
        // \Glimmer\Tenancy\Tasks\SwitchDatabaseConnectionTask::class,
        // \Glimmer\Tenancy\Tasks\PrefixFilesystemTask::class,
        // \Glimmer\Tenancy\Tasks\PrefixScoutTask::class,
        // \Glimmer\Tenancy\Tasks\PrefixSpatiePermissionTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must extend `Spatie\Multitenancy\Models\Tenant::class` or
     * implement `Spatie\Multitenancy\Contracts\IsTenant::class` interface
     */
    'tenant_model' => Glimmer\Tenancy\Models\Tenant::class,

    /*
     * If there is a current tenant when dispatching a job, the id of the current tenant
     * will be automatically set on the job. When the job is executed, the set
     * tenant on the job will be made current.
     */
    'queues_are_tenant_aware_by_default' => true,

    /*
     * This key will be used to associate the current tenant in the context
     */
    'current_tenant_context_key' => 'tenantId',

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * Set it to `true` if you like to cache the tenant(s) routes
     * in a shared file using the `SwitchRouteCacheTask`.
     */
    'shared_routes_cache' => false,

    /*
     * Set it to `true` if you like the package to automatically register the routes
     * for the tenant and landlord from the files `routes/tenant.php` and `routes/landlord.php`
     */
    'use_default_routes_groups' => true,

    /*
     * You can customize some of the behavior of this package by using your own custom action.
     * Your custom action should always extend the default one.
     */
    'actions' => [
        'make_tenant_current_action' => MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => MakeQueueMaybeTenantAwareAction::class,
        'migrate_tenant' => MigrateTenantAction::class,
    ],

    /*
     * You can customize the way in which the package resolves the queueable to a job.
     *
     * For example, using the package laravel-actions (by Loris Leiva), you can
     * resolve JobDecorator to getAction() like so: JobDecorator::class => 'getAction'
     */
    'queueable_to_job' => [
        SendQueuedMailable::class => 'mailable',
        SendQueuedNotifications::class => 'notification',
        CallQueuedClosure::class => 'closure',
        CallQueuedListener::class => 'class',
        BroadcastEvent::class => 'event',
    ],

    /*
     * Jobs tenant aware even if these don't implement the TenantAware interface.
     */
    'tenant_aware_jobs' => [
        // ...
    ],

    /*
     * Jobs not tenant aware even if these don't implement the NotTenantAware interface.
     */
    'not_tenant_aware_jobs' => [
        // ...
    ],

    /*
     * Jobs maybe tenant aware even if these don't implement the MaybeTenantAware interface.
     */
    'maybe_tenant_aware_jobs' => [
        // ...
    ],
];
