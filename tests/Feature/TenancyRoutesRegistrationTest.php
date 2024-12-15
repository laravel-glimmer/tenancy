<?php

it('auto-registers tenant routes', function () {
    expect(route('tenant.tenant', 1))->toBeUrl();
});

it('auto-registers landlord routes', function () {
    expect(route('landlord.landlord'))->toBeUrl();
});
