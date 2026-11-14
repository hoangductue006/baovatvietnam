<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $fillable = ['user_id','correct_answers','total_questions','score','details'];
    protected function casts(): array { return ['details' => 'array']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
