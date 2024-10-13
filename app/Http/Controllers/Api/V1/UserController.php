<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Método responsável por lsitar todos os usuário.
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Método responsável por cadastrar um usuário.
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $created = User::create($data);

        if ($created) {
            return $this->response('User creacted', 200, new UserResource($created));
        }

        return $this->error('User not created', 400, []);
    }

    /**
     * Método responsável por lsitar um unico os usuário.
     */
    public function show(string $id)
    {
        return new UserResource(User::where('id', $id)->first());
    }

    /**
     * Método responsável por atualizar um usuário.
     */
    public function update(UserRequest $request, string $id)
    {
        $validator = User::where('id', $id)->get();

        if (empty($validator->toArray())) {
            return $this->error('Unauthenticated', 403);
        }

        $validated = $request->all();
        $user = User::find($id);

        $updated = $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        if ($updated) {
            return $this->response('User updeted', 200, new User($user->load('user')));
        }

        return $this->error('User not updated', 400, []);
    }
}
