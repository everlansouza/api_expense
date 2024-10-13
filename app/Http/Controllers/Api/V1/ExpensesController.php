<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\V1\ExpensesResource;
use App\Models\Expenses;
use App\Notifications\ExpensesNotification;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Notification;

class ExpensesController extends Controller
{
    use HttpResponses;

    /**
     * Método responsável por listar todos as cobranças.
     */
    public function index()
    {
        $id = auth()->user()->id;

        return ExpensesResource::collection(Expenses::where('id', $id)->get());
    }

    /**
     * Método responsável por cadastrar todos as cobranças.
     */
    public function store(ExpensesRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $created = Expenses::create($data);

        if ($created) {
            Notification::send($created->user, new ExpensesNotification($created));

            return $this->response('Expense creacted', 201, new ExpensesResource($created->load('user')));
        }

        return $this->error('Expense not created', 401, []);
    }


    /**
     * Método responsável por listar uma unica cobranças.
     */
    public function show(string $id)
    {
        return new ExpensesResource(Expenses::where('id', $id)->get());
    }

    /**
     * Método responsável por editar uma cobranças.
     */
    public function update(ExpensesRequest $request, string $id)
    {
        $validated = $request->all();
        $user_id = auth()->user()->id;
        $expense = Expenses::find($id);

        $validator = Expenses::where('id', $id)->where('user_id', $user_id)->get();

        if (empty($validator->toArray())) {
            return $this->error('Unauthenticated', 403);
        }

        $updated = Expenses::find($id)->update([
            "user_id" => $user_id,
            "value" => $validated['value'],
            "date_expenses" => $validated['date_expenses'],
            "description" => $validated['description']
        ]);

        if ($updated) {
            Notification::send($expense->load('user'), new ExpensesNotification($expense->load('user')));

            return $this->response('Expense updeted', 200, new ExpensesResource($expense->load('user')));
        }

        return $this->error('Expense not updated', 403);
    }

    /**
     * Método responsável por remover uma cobranças.
     */
    public function destroy(string $id)
    {
        $expense = Expenses::find($id);

        $deleted = $expense->delete()->where('user_id', $id);

        if ($deleted) {
            return $this->response('Expense deleted', 200, []);
        }

        return $this->error('Expense not deleted', 400);
    }
}
