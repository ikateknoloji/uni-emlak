<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;

    /**
     * Tüm kullanıcıları getirir ve resim bilgilerini tam URL olarak döner.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers()
    {
        return response()->json([
            'message' => 'Tüm kullanıcılar başarıyla getirildi!',
            'users'   => UserResource::collection(User::all()),
        ]);
    }

    /**
     * Kullanıcının şifresini sıfırlar.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => $this->passwordRules(),
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Şifre başarıyla sıfırlandı!',
        ]);
    }

    /**
     * Kullanıcının rolünü günceller.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Rol güncelleme işlemi başarısız!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'message' => 'Kullanıcı rolü başarıyla güncellendi!',
            'user'    => new UserResource($user),
        ]);
    }

    /**
     * Kullanıcıyı siler.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'confirmation' => 'required|string|in:Kullanıcıyı Sil',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Silme işlemi onaylanmadı!',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Kullanıcı başarıyla silindi!',
        ]);
    }
}
