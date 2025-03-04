<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
        public function show()
        {
            $user = Auth::user();
            return new UserResource($user);
        }

        public function update(UpdateUserRequest $request)
        {
            $user = $request->user();

            $data = $request->validated();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo_path) {
                    Storage::delete($user->profile_photo_path);
                }
                $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $user->update($data);

            return new UserResource($user);
        }

        public function updatePassword(Request $request)
        {
            $request->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'La contraseña actual no es correcta'], 422);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json(['message' => 'Contraseña actualizada correctamente']);
        }
}
