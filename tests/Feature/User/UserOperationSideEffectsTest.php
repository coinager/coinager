<?php

use App\Enums\PanelId;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Mail\SendUserCreatedMail;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    PanelId::FAMILY->setCurrentPanel();
});

it('sends email to user when created', function () {
    $newData = User::factory()->make();
    Mail::fake();

    livewire(CreateUser::class)
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    Mail::assertQueued(function (SendUserCreatedMail $mail) {
        return $mail->afterCommit;
    });
});
