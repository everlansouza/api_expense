<?php

namespace Tests\Feature;

use App\Models\Expenses;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    public function testUserCanLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'name' => "Teste01",
            'email' => "teste1@teste.com",
            'password' => 'password',
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function testUserCannotLoginWithInvalidCredentials()
    {
        $response = $this->post('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(401);
    }

    public function testUnauthenticatedUserCannotAccessExpenses()
    {
        $response = $this->getJson('/api/expenses');
        $response->assertStatus(401);
    }

    public function testAuthenticatedUserCanCreateExpense()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/expenses', [
            'descricao' => 'Compra de material',
            'data' => '2023-10-10 00:00:00',
            'valor' => 100.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'descricao', 'data', 'valor']);
    }

    public function testOnlyOwnerCanUpdateExpense()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expenses::factory()->create(['user_id' => $user->id]);

        $this->actingAs($otherUser, 'api');
        $response = $this->putJson("/api/expenses/{$expense->id}", [
            'descricao' => 'Updated Expense',
            'data' => '2023-10-12',
            'valor' => 200.00,
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function testCannotCreateExpenseWithFutureDate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/expenses', [
            'descricao' => 'Compra futura',
            'data' => now()->addDay(),
            'valor' => 100.00,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);
    }

    public function testCannotCreateExpenseWithNegativeValue()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/expenses', [
            'descricao' => 'Compra inválida',
            'data' => now(),
            'valor' => -50.00,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor']);
    }

    public function testOnlyOwnerCanViewExpense()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $expense = Expenses::factory()->create(['user_id' => $user->id]);

        $this->actingAs($otherUser, 'api');
        $response = $this->getJson("/api/expenses/{$expense->id}");

        $response->assertStatus(403);
    }
}
