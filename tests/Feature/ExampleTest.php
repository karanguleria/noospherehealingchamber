<?php

it('redirects guests to the login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});

it('renders the Nova login page', function () {
    $this->get('/nova/login')->assertOk();
});
