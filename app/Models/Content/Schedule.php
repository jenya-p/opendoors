<?php

namespace App\Models\Content;

use App\Models\Ordered;
use App\Models\Translable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id

 * @property Carbon $date_from
 * @property Carbon $date_to
 * @property string $title
 * @property string $title_en
 * @property string $summary
 * @property string $summary_en
 * @property string $details
 * @property string $details_en
 *
 * @property-read $dateRangeDescription
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 *
 * @mixin \Eloquent
 */
class Schedule extends Model
{
    use HasFactory, Translable;

    protected $table = 'schedule_items';

    protected $fillable = ['date_from','date_to','title','title_en','summary','summary_en','details','details_en'];

    protected $casts = [
        'date_from' => 'date:Y-m-d',
        'date_to' => 'date:Y-m-d',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $translable = [
        'title',
        'summary',
        'details'
    ];

    public function getDateRangeDescriptionAttribute(){
        if(empty($this->date_to) || $this->date_to->format('Y-m-d') == $this->date_from->format('Y-m-d')) {
            return $this->date_from->translatedFormat('j F Y');
        }
        if($this->date_from->year == $this->date_to->year){

            if($this->date_from->month == $this->date_to->month){
                return $this->date_from->format('j') . ' - ' .$this->date_to->translatedFormat('j F Y');
            } else {
                return $this->date_from->translatedFormat('j F') . ' - ' .$this->date_to->translatedFormat('j F Y');
            }
        } else {
            return $this->date_from->translatedFormat('j F Y') . ' - ' . $this->date_to->translatedFormat('j F Y');
        }
    }

}
