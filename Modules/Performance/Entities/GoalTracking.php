<?php

namespace Modules\Performance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'goal_type',
        'start_date',
        'end_date',
        'subject',
        'target_achievement',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\GoalTrackingFactory::new();
    }
    public function goalType()
    {
        return $this->hasOne(GoalType::class, 'id', 'goal_type');
    }

    public function branches()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Branch::class, 'id', 'branch');
    }

    public static $status = [
        'Not Started',
        'In Progress',
        'Completed',
    ];
    public static function getProgressColor($percentage)
    {
        $color = '';
        if ($percentage <= 20) {
            $color = 'danger';
        } elseif ($percentage > 20 && $percentage <= 40) {
            $color = 'warning';
        } elseif ($percentage > 40 && $percentage <= 60) {
            $color = 'info';
        } elseif ($percentage > 60 && $percentage <= 80) {
            $color = 'primary';
        } elseif ($percentage >= 80) {
            $color = 'success';
        }
        return $color;
    }
}
