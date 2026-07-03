<?php

use App\Filament\Resources\BankAccounts\Pages\ManageBankAccounts;
use App\Models\BankAccount;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('can render page', function () {
    $user = User::factory()->createOne();

    actingAs($user)
        ->get(ManageBankAccounts::getUrl())
        ->assertSuccessful();
});

it('can list bank accounts', function () {
    $user = User::factory()->create();
    $bank_account = BankAccount::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ManageBankAccounts::class)
        ->assertCanSeeTableRecords([$bank_account]);
});

it('cannot see other user bank accounts', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $other_bank_account = BankAccount::factory()->for($other_user)->create();

    Livewire::actingAs($user)
        ->test(ManageBankAccounts::class)
        ->assertCanNotSeeTableRecords([$other_bank_account]);
});

it('can create bank account', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ManageBankAccounts::class)
        ->mountAction('create')
        ->fillForm([
            'name' => 'My Main Bank Account',
            'balance' => '57294.00',
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    assertDatabaseHas('bank_accounts', [
        'user_id' => $user->id,
        'name' => 'My Main Bank Account',
        'balance' => 5729400,
    ]);
});

it('can update bank account', function () {
    $user = User::factory()->create();
    $bank_account = BankAccount::factory()->for($user)->create([
        'name' => 'Old bank name',
        'balance' => 5000,
    ]);

    Livewire::actingAs($user)
        ->test(ManageBankAccounts::class)
        ->mountAction(TestAction::make('edit')->table($bank_account))
        ->fillForm([
            'name' => 'New bank name',
            'balance' => '400.00',
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    assertDatabaseHas('bank_accounts', [
        'id' => $bank_account->id,
        'name' => 'New bank name',
        'balance' => 40000,
    ]);
});

it('can delete bank account', function () {
    $user = User::factory()->create();
    $bank_account = BankAccount::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(ManageBankAccounts::class)
        ->mountTableAction('delete', $bank_account)
        ->callMountedAction()
        ->assertHasNoTableActionErrors();

    assertDatabaseMissing('bank_accounts', [
        'id' => $bank_account->id,
    ]);
});
