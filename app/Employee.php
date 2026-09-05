<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use DateTime;

class Employee extends Model implements HasMedia
{
    use HasMediaTrait;
    protected $guarded = [];
    protected $fillable = [
        /*'employee_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'birthdate',
        'media_id',
        'address',
        'gender',
        'remark',
        'position_id',
        'schedule_id',
        'rate_per_hour',
        'salary',
        'is_active',*/
    ];

    protected $hidden = [
        'avatarMedia',
    ];

    protected $appends = ['media_url','created_on','total_working_hour','gross_amount'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getRouteKeyName()
    {
        return 'employee_id';
    }

    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucwords($value);
    }

    protected static function boot()
    {
    	parent::boot();
    	static::creating(function($employee){
    		$employee->employee_id = strtoupper(uniqid("EMP"));
    	});
    }

    public function registerMediaCollections(){
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function(Media $media){
                $this->addMediaConversion('thumb')
                ->format('png')
                ->width(128)
                ->height(128);
            });
    }

    public function position(){
        return $this->hasOne(Position::class,'id','position_id');
    }

    public function schedule(){
        return $this->hasOne(Schedule::class,'id','schedule_id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'employee_id','id');
    }
    public function attendancesfuture(){
        return $this->hasMany(Attendance::class,'employee_id','id');
    }

    public function overtimes(){
        return $this->hasMany(Overtime::class,'employee_id','id');
    }

    public function cashAdvances(){
        return $this->hasMany(CashAdvance::class,'employee_id','id');
    }

    public function getTotalWorkingHourAttribute(){
        $futuredata = !empty($_REQUEST['futuredata'])?$_REQUEST['futuredata']:0;
        if($futuredata==1)
        {
            $durationDate = !empty($_REQUEST['date'])?$_REQUEST['date']:'';
            if(!empty($durationDate))
            {

                $onHold = $this->biss_hours();
                $num_mins = $onHold*60;

            }
            return $num_mins;
        }
        else
        {
            return $this->attendances->sum('num_hour');
        }

    }

    public function getGrossAmountAttribute(){
        $durationDate = !empty($_REQUEST['date'])?$_REQUEST['date']:'';
        if(!empty($durationDate))
        {

            $onHold = $this->biss_hours();
            $num_mins = $onHold*60;

        }

        $futuredata = !empty($_REQUEST['futuredata'])?$_REQUEST['futuredata']:0;
        if($this->attributes['pay_type']=='salary')
        {
            if($futuredata==1)
            {
                $num_days = 1;
            }
            else
            {
                $num_days = count($this->attendances);
            }

            if($num_days>0)
            {
                $perdaySalary = $this->attributes['salary']/$num_days;
            }
            else
            {
                $perdaySalary=0;
            }
            return ($num_days * floatval(preg_replace('/[^\d.]/', '', $perdaySalary)));
        }
        else
        {
            if($futuredata==1)
            {
                return ($num_mins * floatval(preg_replace('/[^\d.]/', '', $this->attributes['rate_per_hour'])))/60;
            }
            else
            {
                return ($this->attendances->sum('num_hour') * floatval(preg_replace('/[^\d.]/', '', $this->attributes['rate_per_hour'])))/60;
            }

        }

    }

    protected function avatarMedia(){
        return $this->hasOne(Media::class,'id','media_id');
    }

    public function getMediaUrlAttribute(){
        $avatar = strtolower($this->attributes['gender'].'.png');
        $url = [
            'original' => url('public/admin_assets/avatars/employee/'.$avatar),
            'thumb' => url('public/admin_assets/avatars/employee/thumb/'.$avatar),
        ];
        if(!is_null($this->attributes['media_id']) && !is_null($this->avatarMedia)){
            $imgurl = $this->avatarMedia->getFullUrl();
            // $imgHeaders = @get_headers( str_replace(" ", "%20", $imgurl) )[0];
            // if(file_exists($this->avatarMedia->getPath()) && ($imgHeaders != 'HTTP/1.1 404 Not Found')){
                $url = [
                    'original' => $this->avatarMedia->getFullUrl(),
                    'thumb' => $this->avatarMedia->getFullUrl('thumb'),
                ];
            // }
        }
        return $url;
    }

    public function getCreatedOnAttribute(){
        $dt = $this->attributes['created_at'];
        $date = date('M d, Y', strtotime($dt));
        return $date;
    }
    /*You take your start date and calculate the rest time on this day (if it is a business day)
You take your end date and calculate the time on this day and
take the days in between and multiply them with your business hours (just those,   that are business days)
Day start is 08:30 and day end is 17:30 so 9 hour working days*/

    function biss_hours($start='', $end=''){

        $durationDate = !empty($_REQUEST['date'])?$_REQUEST['date']:'';
        $dateTemp = explode(' - ', $durationDate);
        $start = date("Y-m-d",strtotime($dateTemp[0]));
        $end = date("Y-m-d",strtotime($dateTemp[1]));
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        //Set the start and end dates to the start of the working day
        $startofday = clone $startDate;
        $startofday->setTime(8,30);
        $enddaystart = clone $endDate;
        $enddaystart->setTime(8,30);

        //get the rest time on start day in hours
        $t1 = $startofday->format('Y-m-d H:i:s');
        $t2 = $startDate->format('Y-m-d H:i:s');
        $firstDayRest = $this->calculate_hours($t1, $t2);

        //get the rest time on start day in hours
        $t3 = $enddaystart->format('Y-m-d H:i:s');
        $t4 = $endDate->format('Y-m-d H:i:s');
        $lastDayRest = $this->calculate_hours($t3, $t4);
        //Get the number of days between the two dates
       $daysBetween = $this->getWeekdayDifference($start, $end);
       if($daysBetween==22)
       {
           $daysBetween=$daysBetween+1;
       }
        //multiply the days by the 8 working hours
        $hoursBetween = $daysBetween * 8;
        //add the rest times onto the number of working hours between the two dates.
        //return $hoursBetween + $firstDayRest + $lastDayRest;
        return $hoursBetween;

    }


//returns the hours between two dates
    function calculate_hours($t1, $t2){
        $t1 = StrToTime ($t1);
        $t2 = StrToTime ($t2);

        $diff = $t2 - $t1;
        $hours = $diff / ( 60 * 60 );

        return $hours;
    }
//returns the number of days (excluding weekends) between two dates
    function getWeekdayDifference($startDate, $endDate)
    {
        $days = 0;

        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        while($startDate->diff($endDate)->days > 0) {
            $days += $startDate->format('N') <6 ? 1 : 0;
            $startDate = $startDate->add(new \DateInterval("P1D"));
   }

        return $days;
    }


}
