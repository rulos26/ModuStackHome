<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's image URL for AdminLTE.
     */
    public function adminlte_image(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        // Imagen por defecto si no tiene foto
        return asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    /**
     * Get the user's description for AdminLTE.
     */
    public function adminlte_desc(): string
    {
        return $this->email;
    }

    /**
     * Get the user's profile URL for AdminLTE.
     */
    public function adminlte_profile_url(): string
    {
        return 'perfil';
    }
}
