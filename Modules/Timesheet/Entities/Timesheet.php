<?php

namespace Modules\Timesheet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'date',
        'hours',
        'type',
        'notes',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Timesheet\Database\factories\TimesheetFactory::new();
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne('\Modules\Taskly\Entities\Project', 'id', 'project_id');
    }

    public function task()
    {
        return $this->hasOne('\Modules\Taskly\Entities\Task', 'id', 'task_id');
    }
}
