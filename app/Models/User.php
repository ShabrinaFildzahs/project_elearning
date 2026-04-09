<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin() { return $this->role === 'admin'; }
    public function isGuru() { return $this->role === 'guru'; }
    public function isSiswa() { return $this->role === 'siswa'; }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function academicMaps()
    {
        return $this->hasMany(AcademicMap::class, 'teacher_id');
    }
}
