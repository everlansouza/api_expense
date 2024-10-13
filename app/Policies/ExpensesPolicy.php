<?php

namespace App\Policies;

use App\Models\Expenses;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensesPolicy
{
    public function show(User $user, Expenses $expenses)
    {
        return $expenses->user_id == $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expenses $expenses)
    {
        return $expenses->user->is($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expenses $expenses)
    {
        return $expenses->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expenses $expenses)
    {
        return $expenses->user->is($user);
    }
}
