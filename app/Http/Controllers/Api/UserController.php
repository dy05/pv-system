<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'users' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'last_name' => 'string',
            'first_name' => 'string',
            'phone' => 'string',
            'email' => 'string|required|unique:users',
            'password' => 'string|confirmed|required',
            'avatar' => 'file'
        ]);

        $result = [];

        try {
            $user = new User($data);
            if ($user->save()) {
                $result = [
                    'message' => 'User created successfully.'
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Show all Users with deleted account
     *
     * @return JsonResponse
     */
    public function getArchived(): JsonResponse
    {
        return response()->json([
            'users' => User::onlyTrashed()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'last_name' => 'string',
            'first_name' => 'string',
            'phone' => 'string',
//            'email' => 'string|unique:users',
            'email' => 'string|unique:users,email,' . $user->id,
            'avatar' => 'file'
        ]);

        $result = [];

        try {
            if ($user->update($data)) {
                $result = [
                    'message' => 'User updated successfully.'
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $result = [];

        try {
            if ($user->delete()) {
                $result = [
                    'message' => 'User deleted successfully.'
                ];
            }
        } catch (Exception $exc) {
            $result = [
                'error' => 'Unexpected Error: ' . $exc->getMessage()
            ];
        }

        return response()->json($result);
    }
}
