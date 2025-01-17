<?php

declare(strict_types=1);

use Saloon\Helpers\Date;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Laravel\Tests\Fixtures\Models\EncryptedOAuthModel;

test('the authenticator can be encrypted, serialized and decrypted and unserialized when using the cast', function () {
    $model = new EncryptedOAuthModel();
    $authenticator = new AccessTokenAuthenticator('access', 'refresh', Date::now()->toDateTime());

    $model->auth = $authenticator;

    $rawAuth = $model->getAttributes()['auth'];

    expect($rawAuth)->toBeString();
    expect(decrypt($rawAuth))->toEqual(serialize($authenticator));

    // Now lets try to use the accessor

    $modelAuth = $model->auth;

    expect($modelAuth)->toEqual($authenticator);
});

test('the cast will accept null', function () {
    $model = new EncryptedOAuthModel();
    $model->auth = null;

    expect($model->auth)->toBeNull();
});

test('it will throw an exception if you pass in a value that is not null', function () {
    $model = new EncryptedOAuthModel();
    $model->auth = 'Hello';
})->throws(InvalidArgumentException::class, 'The given value is not an OAuthAuthenticator instance.');
