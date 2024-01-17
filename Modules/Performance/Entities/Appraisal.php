<?php

namespace Modules\Performance\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Performance\Entities\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'employee',
        'appraisal_date',
        'customer_experience',
        'marketing',
        'administration',
        'professionalism',
        'integrity',
        'attendance',
        'remark',
        'workspace',
        'created_by',
    ];
    
    public static $technical = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
        'Expert / Leader',
    ];

    public static $organizational = [
        'None',
        'Beginner',
        'Intermediate',
        'Advanced',
    ];
    protected static function newFactory()
    {
        return \Modules\Performance\Database\factories\AppraisalFactory::new();
    }
    public function branches()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Branch::class, 'id', 'branch');
    }

    public function employees()
    {
        return $this->hasOne(\Modules\Hrm\Entities\Employee::class, 'id', 'employee');
    }
    public static function getTargetrating($designationid, $competencyCount)
    {
        $indicator = Indicator::where('designation', $designationid)->first();

        if (!empty($indicator->rating) && ($competencyCount != 0))
        {
            $rating = json_decode($indicator->rating, true);
            $starsum = array_sum($rating);

            $overallrating = $starsum / $competencyCount;
        } else {
            $overallrating = 0;
        }
        return $overallrating;
    }
}
