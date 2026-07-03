<?php

use App\Enums\TransactionType;
use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

it('cannot see other users transactions', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();

    $other_transaction = Transaction::factory()->for($other_user)->create();

    Livewire::actingAs($user)
        ->test(ListTransactions::class)
        ->assertCanNotSeeTableRecords([$other_transaction]);
});

it('can create transaction', function () {
    $user = User::factory()->create();
    $bank_account = BankAccount::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(CreateTransaction::class)
        ->fillForm([
            'date' => '2025-01-30',
            'type' => TransactionType::Expense,
            'amount' => '150.00',
            'description' => 'Test Transaction',
            'bank_account_id' => $bank_account->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'bank_account_id' => $bank_account->id,
        'description' => 'Test Transaction',
        'amount' => -15000,
    ]);
});

it('can create transaction for other user account', function () {
    $user = User::factory()->create();
    $other_user = User::factory()->create();
    $other_bank_account = BankAccount::factory()->for($other_user)->create();

    Livewire::actingAs($user)
        ->test(CreateTransaction::class)
        ->fillForm([
            'date' => '2026-01-30',
            'transaction_type' => TransactionType::Expense,
            'amount' => '150.00',
            'description' => 'Test Transaction',
            'bank_account_id' => $other_bank_account->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['bank_account_id']);
});
