<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    // Exibir todos os usuários
    public function index()
    {
        $users = User::paginate(15);
        return response()->json($users);
    }


    // Armazenar um novo usuário
    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        return response()->json(['message' => 'User created successfully.', 'User' => $user],204);
    }

    // Exibir um único usuário
    public function show($user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json($user);
    }

    // Atualizar um usuário
    public function update(Request $request, $user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully.', 'User' => $user], 204);
    }

    // Remover um usuário
    public function destroy($user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->delete();

        return response()->json(['success' => 'User deleted successfully.'], 204);
    }
}
