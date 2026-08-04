<?php

test('halaman utama mengarahkan pengguna ke halaman login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});