<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'cpf' => 'required|string|size:11',
                'phone' => 'required|string|size:11|regex:/^\d{11}$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string',
                'address.street' => 'required|string|max:255',
                'address.number' => 'nullable|string|max:255',
                'address.neighborhood' => 'required|string|max:255',
                'address.city' => 'required|string|max:255',
                'address.state' => 'required|string|max:255',
                'address.zip_code' => 'required|string|size:8|regex:/^\d{8}$/'
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'cpf' => $validatedData['cpf'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password'])
            ]);

            Address::create([
                'street' => $validatedData['address']['street'],
                'number' => $validatedData['address']['number'],
                'neighborhood' => $validatedData['address']['neighborhood'],
                'city' => $validatedData['address']['city'],
                'state' => $validatedData['address']['state'],
                'zip_code' => $validatedData['address']['zip_code'],
                'user_id' => $user->id
            ]);

            DB::commit();

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $user->createToken('')->plainTextToken
            ]);
        } catch(ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch(Throwable $th) {
            DB::rollBack();

            report($th);

            return response()->json(['status' => 'error'], 500);
        }
    }
}
