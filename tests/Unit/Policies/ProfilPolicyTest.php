<?php

declare(strict_types=1);

use App\Models\Profil;
use App\Models\User;
use App\Policies\ProfilPolicy;
use Tests\TestCase;

// Utilise Tests\TestCase pour que Eloquent soit correctement initialisé.
uses(TestCase::class);

beforeEach(function () {
    $this->policy = new ProfilPolicy;
    $this->user = new User;
    $this->profil = new Profil;
});

it('viewAny retourne false', function () {
    expect($this->policy->viewAny($this->user))->toBeFalse();
});

it('view retourne false', function () {
    expect($this->policy->view($this->user, $this->profil))->toBeFalse();
});

it('create retourne false', function () {
    expect($this->policy->create($this->user))->toBeFalse();
});

it('update retourne false', function () {
    expect($this->policy->update($this->user, $this->profil))->toBeFalse();
});

it('delete retourne false', function () {
    expect($this->policy->delete($this->user, $this->profil))->toBeFalse();
});

it('restore retourne false', function () {
    expect($this->policy->restore($this->user, $this->profil))->toBeFalse();
});

it('forceDelete retourne false', function () {
    expect($this->policy->forceDelete($this->user, $this->profil))->toBeFalse();
});
