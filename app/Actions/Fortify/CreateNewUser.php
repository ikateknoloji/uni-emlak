<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', 'in:user,admin'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ])->validate();

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

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
            'profile_picture' => $profilePicturePath,
        ]);
    }
}
