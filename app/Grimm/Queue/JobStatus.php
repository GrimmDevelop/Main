<?php


namespace Grimm\Queue;

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobStatus extends Eloquent {
    protected $table = 'job_status';

    protected $fillable = ['token', 'handler', 'progress', 'status', 'title', 'user_id', 'percentage'];

    protected $hidden = ['id', 'handler', 'user_id'];

    public function makeRunning()
    {
        $this->status = 1;
    }

    public function makeRetained()
    {
        $this->status = 2;
    }

    public function makeFinished()
    {
        $this->percentage = 100;
        $this->status = 3;
    }

    public function addProgressMessage($message)
    {
        $log = json_decode($this->attributes['progress']);
        $log[] = [date('Y-m-d H:i:s'), $message];

        $this->attributes['progress'] = json_encode($log);
    }

    public function getProgressAttribute($value)
    {
        return json_decode($value);
    }

    public function setProgressAttribute($value)
    {
        $this->attributes['progress'] = json_encode($value);
    }

    public function starter()
    {
        return $this->belongsTo('Grimm\Auth\Models\User', 'user_id');
    }
}