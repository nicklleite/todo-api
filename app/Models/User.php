<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function tasksSummary($period = null) {
        
        [$start, $end] = match ($period) {
            'today' => [Carbon::now()->locale('pt-BR')->startOfDay(), Carbon::now()->locale('pt-BR')->endOfDay()],
            'yesterday' => [Carbon::yesterday()->locale('pt-BR')->startOfDay(), Carbon::yesterday()->locale('pt-BR')->endOfDay()],
            'lastweek', 'last-week' => [Carbon::now()->locale('pt-BR')->subWeek()->startOfWeek(), Carbon::now()->locale('pt-BR')->subWeek()->endOfWeek()],
            'thismonth', 'this-month' => [Carbon::now()->locale('pt-BR')->startOfMonth(), Carbon::now()->locale('pt-BR')->endOfMonth()],
            'lastmonth', 'last-month' => [Carbon::now()->locale('pt-BR')->startOfMonth()->subMonthNoOverflow(), Carbon::now()->locale('pt-BR')->endOfMonth()->subMonthNoOverflow()],

            default => [Carbon::now()->locale('pt-BR')->startOfWeek(), Carbon::now()->locale('pt-BR')->endOfWeek()],
        };

        return $this->tasks()->whereBetween('created_at', [$start, $end])->latest()->get();
    }
}
