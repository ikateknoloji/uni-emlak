<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;
use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    use PasswordValidationRules;

    /**
     * API üzerinden yeni kullanıcı kaydı oluşturur.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $input = $request->all();

        // Gerekli validasyon kurallarını uygula.
        $validator = Validator::make($input, [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password'        => $this->passwordRules(),
            'role'            => ['required', 'string', 'in:user,admin'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $profilePicturePath = null;

        if (isset($input['profile_picture'])) {
            $profilePicturePath = $input['profile_picture']->store('profile_pictures', 'public');
        } else {
            $name = $input['name'];
            $initials = collect(explode(' ', $name))
                ->filter()
                ->map(fn($word) => strtoupper(mb_substr($word, 0, 1)))
                ->join('');

            $manager = new ImageManager(new Driver());
            $image = $manager->create(200, 200)->fill(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));

            $image->text($initials, 100, 100, function (FontFactory $font) {
                $font->filename(public_path('fonts/Roboto-Black.ttf'));
                $font->size(72);
                $font->color(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
                $font->align('center');
                $font->valign('center');
            });

            $profilePicturePath = 'profile_pictures/' . uniqid() . '.png';
            $image->save(storage_path('app/public/' . $profilePicturePath));
        }

        // Kullanıcıyı oluştururken şifreyi hash’leyerek kaydet.
        $user = User::create([
            'name'            => $input['name'],
            'email'           => $input['email'],
            'password'        => Hash::make($input['password']),
            'role'            => $input['role'],
            'profile_picture' => $profilePicturePath,
        ]);

        // NOT: Burada Auth::login() gibi oturum açmayı tetiklemedik.

        return response()->json([
            'message' => 'Kullanıcı başarıyla oluşturuldu!',
            'user'    => UserResource::make($user),
        ], 201);
    }
}
