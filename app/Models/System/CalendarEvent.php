<?php

declare(strict_types=1);

namespace App\Models\System;

use App\Models\Shared\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CalendarEvent Eloquent Model
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Models\System
 */
class CalendarEvent extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var    string
     * @access protected
     */
    protected $table = 'calendar_events';

    /**
     * @var string ACTIVE
     */
    const ACTIVE = 'active';

    /**
     * @var string INACTIVE
     */
    const INACTIVE = 'inactive';

    /**
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::INACTIVE,
        self::ACTIVE
    ];

    /**
     * @var string NEW_YEARS_EVE
     */
    const NEW_YEARS_EVE = '12/31';

    /**
     * @var string NEW_YEARS_DAY
     */
    const NEW_YEARS_DAY = '01/1';

    /**
     * @var string MLK_DAY
     */
    const MLK_DAY = '01/18';

    /**
     * @var string EASTER DAY
     */
    const EASTER_DAY = 'last sunday of march';

    /**
     * @var string GOOD_FRIDAY
     */
    const GOOD_FRIDAY = '04/2';

    /**
     * @var string PRESIDENTS_DAY
     */
    const PRESIDENTS_DAY = 'third monday/OD February';

    /**
     * @var string MEMORIAL_DAY
     */
    const MEMORIAL_DAY = '5/31';

    /**
     * @var string INDEPENDENCE_DAY
     */
    const INDEPENDENCE_DAY = '06/4';

    /**
     * @var string LABOR_DAY
     */
    const LABOR_DAY = '09/6';

    /**
     * @var string COLUMBUS_DAY
     */
    const COLUMBUS_DAY = '10/11';

    /**
     * @var string VETERANS_DAY
     */
    const VETERANS_DAY = '11/11';

    /**
     * @var string CHRISTMAS_EVE
     */
    const CHRISTMAS_EVE = '12/24';

    /**
     * @var string CHRISTMAS_DAY
     */
    const CHRISTMAS_DAY = '12/25';

    /**
     * @var array HOLIDAYS
     */
    const HOLIDAYS = [
        'new_years_eve'     => NEW_YEARS_EVE,
        'new_years'         => NEW_YEARS_DAY,
        'mlk_day'           => MLK_DAY,
        'easter_day'        => EASTER_DAY,
        'good_friday'       => GOOD_FRIDAY,
        'presidents_day'    => PRESIDENTS_DAY,
        'memorial_day'      => MEMORIAL_DAY,
        'independence_day'  => INDEPENDENCE_DAY,
        'labor_day'         => LABOR_DAY,
        'columbus_day'      => COLUMBUS_DAY,
        'veterans_day'      => VETERANS_DAY,
        'christmas_eve'     => CHRISTMAS_EVE,
        'christmas_day'     => CHRISTMAS_DAY,
    ];


    /**
     * Get New Years Eve
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getNewYearsEveDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::NEW_YEARS_EVE.'/'.$year));
    }

    /**
     * Get New Years Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getNewYearsDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::NEW_YEARS_DAY.'/'.$year));
    }

    /**
     * Get MLK Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getMLKDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::MLK_DAY.'/'.$year));
    }

    /**
     * Get Good Friday
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getGoodFriday(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::GOOD_FRIDAY.'/'.$year));
    }

    /**
     * Get Memorial Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getMemorialDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::MEMORIAL_DAY.'/'.$year));
    }

    /**
     * Get Veterans Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getVeteransDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::VETERANS_DAY.'/'."{$year}"));
    }

    /**
     * Get Columbus Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getColumbusDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::COLUMBUS_DAY.'/'.$year));
    }

    /**
     * Get Independence Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getIndependenceDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::INDEPENDENCE_DAY.'/'.$year));
    }

    /**
     * Get Presidents day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getPresidentsDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::PRESIDENTS_DAY." {$year}"));
    }

    /**
     * Get Easter Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getEasterDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::EASTER_DAY." {$year}"));
    }

    /**
     * Get Christmas Eve
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getChristmasEveDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::CHRISTMAS_EVE.'/'.$year));
    }

    /**
     * Get Christmas Day
     *
     * @param  int $year
     * @return string
     * @static
     */
    public static function getChristmasDay(int $year, string $format='d/m/Y')
    {
        $year = safe_integer($year);
        return date($format, strtotime(self::CHRISTMAS_DAY.'/'.$year));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'meta_fields',
        'status',
        'start_at',
        'ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     */
    protected $casts = [
        'id'            => 'integer',
        'uuid'          => 'string',
        'title'         => 'string',
        'meta_fields'   => 'object',
        'start_at'      => 'datetime',
        'ends_at'       => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];
}
