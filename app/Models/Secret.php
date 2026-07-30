<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property String $token
 * @property String $generated_link
 * @property String $message
 * @property User   $user
 * @property Carbon $accessed_at
 * @property Carbon $expires_at
 */
class Secret extends Model {
    /** @use HasFactory<\Database\Factories\SecretFactory> */
    use HasFactory;
    use HasUuids;

    protected $appends = ['generated_link'];

    protected $primaryKey = "token";

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function generateLink() {
        return request()->getHttpHost() . "/secrets/" . $this->token;
    }

    public function markRead() {
        $this->accessed_at = Carbon::now();
        $this->save();
    }

    public function scopeActive(Builder $query) {
        $query->where(function (Builder $query) {
            $query->whereNull('accessed_at')
                  ->where(function (Builder $query) {
                      $query->whereNull("expires_at")
                            ->orWhereTodayOrAfter("expires_at");
                  });
        });
    }

    public function scopeInactive(Builder $query) {
        $query->where(function (Builder $query) {
            $query->whereNotNull('accessed_at')
                  ->orWhereBeforeToday('expires_at');
        });
    }

    public function scopeOwnedByUser(Builder $query) {
        $query->whereBelongsTo(Auth::user());
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array {
        return ['token'];
    }

    protected function generatedLink() : Attribute {
        return new Attribute(
            get: fn() => $this->generateLink(),
        );
    }
}
