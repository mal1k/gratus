<?php

namespace App\Http\Controllers\Api\Admin;

use DateTime;
use DateTimeImmutable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\{
    Organization,
    Receiver,
    Tipper,
    Transactions,
};

class StatsController extends Controller
{
    private $_months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    private $_week = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];

    /**
     * The main method of getting stats from the database
     */
    public function __invoke()
    {
        $type = Request::input('timeType');
        $range = Request::input('range');
        $modelName = Request::input('model');

        if (
            !$type ||
            !$range ||
            !isset($range['begin']) ||
            !isset($range['end']) ||
            !$modelName
        ) {
            return false;
        }

        $class = $this->getClass($modelName);

        if ($type === 'day') {
            list($period, $dates) = $this->getPeriodByDay($range);
            $stats = $this->getStatsByDay($class, $dates);
        } else if ($type === 'week') {
            $period = $this->getPeriodByWeek($range);
            $stats = $this->getStatsByWeek($class, $period);
        } else if ($type === 'month') {
            $period = $this->getPeriodByMonth($range);
            $stats = $this->getStatsByMonth($class, $period);
        } else {
            abort(500, 'There are only 3 options valid. It is "week", "day", and "month".');
        }

        if (isset($period['today'])) {
            unset($period['today']);
        }

        return response()->json([
            'labels' => $period,
            'datasets' => [[
                'label' => 'Dataset 1',
                'data' => $stats,
            ]]
        ]);
    }

    /**
     * Get the class for getting statistics
     */
    protected function getClass(string $class) : string
    {
        if ($class === 'organizations') {
            return Organization::class;
        } else if ($class === 'receivers') {
            return Receiver::class;
        } else if ($class === 'tippers') {
            return Tipper::class;
        } else if ($class === 'revenue') {
            return Transactions::class;
        } /* else if ($class === 'event') {
            return Event::class;
        } else if ($class === 'revenue') {
            return Payment::class;
        } else if ($class === 'subscription') {
            return Subscription::class;
        } */ else {
            abort(500, 'The Model name did not recognized');
        }
    }

    protected function getPeriodByDay(array $range) : array
    {
        $moment = new DateTimeImmutable();
        if ( $range['end'] === 'today' ) {
            $date = $moment;
            $direction = '-1 day';
            $count = $range['begin'];
        } else if ($range['end'] === 'all') {
            $date = new DateTime($moment->format('Y') .'-'. $moment->format('m') .'-01');
            $direction = '+1 day';
            $count = $moment->format('t');
        } else {
            abort(500, 'There are only 2 options valid. There are "today" and "all".');
        }

        for ($i = 0; $i < $count; ++$i)
        {
            if ($i > 0) {
                $date = $date->modify($direction);
            }

            $postfix = $date->format('S');
            $day = $date->format('j');
            $dataWithPrefix[] = $day . $postfix;
            $dataWithoutPrefix[] = $date->format('Y-m-d');
        }

        if ( $range['end'] === 'today' ) {
            $dataWithoutPrefix['today'] = true;
            return [array_reverse($dataWithPrefix), array_reverse($dataWithoutPrefix)];
        } else {
            return [$dataWithPrefix, $dataWithoutPrefix];
        }
    }

    protected function getPeriodByWeek(array $range) : array
    {
        if ($range['end'] === 'today') {
            $currentDayOfWeek = (new DateTime('now'))->format('l');
            $daysOfWeek = $this->getTimeItems('week', [
                'current' => $currentDayOfWeek,
                'amount' => $range['begin'],
            ]);
        } else if ($range['end'] === 'all') {
            $daysOfWeek = $this->getTimeItems('week', [
                'amount' => $range['begin'],
            ]);
        } else {
            abort(500, 'There are only 2 options valid. There are "today" and "all".');
        }

        return $daysOfWeek;
    }

    protected function getPeriodByMonth(array $range) : array
    {
        if ($range['end'] === 'today') {
            $currentMonth = (new DateTime('now'))->format('M');
            $months = $this->getTimeItems('month', [
                'current' => $currentMonth,
                'amount' => $range['begin'],
            ]);
        } else if ($range['end'] === 'all') {
            $months = $this->getTimeItems('month', [
                'amount' => $range['begin'],
            ]);
        } else {
            abort(500, 'There are only 2 options valid. There are "today" and "all".');
        }

        return $months;
    }

    protected function getTimeItems(string $dataType, array $config) : array
    {
        if ($dataType === 'month') {
            $defaultNumber = 12;
            $items = $this->_months;
        } else if ($dataType === 'week') {
            $defaultNumber = 7;
            $items = $this->_week;
        } else {
            abort(500, 'There are only 2 options. It is "month" and "week".');
        }

        $cfg = $config ?? [];
        $amount = $cfg['amount'] ?? $defaultNumber;
        $values = [];
        $value = '';

        if ( isset($cfg['current']) ) {
            $items = array_reverse($items);
            if ( ($index = array_search($cfg['current'], $items)) ) {
                $slice = array_slice($items, $index);
            }

            if (isset($slice)) {
                $countSlice = count($slice);
                for ($i = 0; $i < $countSlice; ++$i)
                {
                    $value = $slice[round($i) % $countSlice];
                    $values[] = $value;
                    $amount--;
                }
                $values['today'] = true;
            }
        }

        for ($i = 0; $i < $amount; ++$i)
        {
            $value = $items[round($i) % $defaultNumber];
            $values[] = $value;
        }

        if ( isset($cfg['current']) ) {
            $values = array_reverse($values);
        }

        return $values;
    }

    protected function getStatsByDay(string $class, array $period) : array
    {
        $stats = [];

        $moment = new DateTimeImmutable('now');
        $isToday = false;
        if ( isset($period['today']) ) {
            $isToday = $period['today'];
            unset($period['today']);
        }
        foreach ( $period as $value )
        {
            $date = new DateTime($value);

            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $between = [
                $year .'-'. $month .'-'. $day .' 00:00:00',
                $year .'-'. $month .'-'. $day .' 23:59:59'
            ];

            $stats[] = $class::whereBetween('created_at', $between)->count();

            if ( !$isToday ) {
                if ($date->format('Y-m-d') == $moment->format('Y-m-d')) {
                    break;
                }
            }
        }

        return $stats;
    }

    protected function getStatsByWeek(string $class, array $period) : array
    {
        $stats = [];
        $moment = new DateTimeImmutable('now');
        if ( isset($period['today']) ) {
            unset($period['today']);
            for ($i = 0; $i < count($period); ++$i)
            {
                if ($i > 0) {
                    $moment = $moment->modify('-1 day');
                }

                $year = $moment->format('Y');
                $month = $moment->format('m');
                $day = $moment->format('d');
                $between = [
                    $year .'-'. $month .'-'. $day .' 00:00:00',
                    $year .'-'. $month .'-'. $day .' 23:59:59'
                ];

                $stats[] = $class::whereBetween('created_at', $between)->count();
            }

            $stats = array_reverse($stats);

        } else {
            $currentWeek = $this->getCurrentWeek($moment);

            for ($i = 0; $i < count($period); ++$i)
            {
                if ($i === 0) {
                    $date = new DateTime($currentWeek[0]);
                }

                $year = $date->format('Y');
                $month = $date->format('m');
                $day = $date->format('d');
                $between = [
                    $year .'-'. $month .'-'. $day .' 00:00:00',
                    $year .'-'. $month .'-'. $day .' 23:59:59'
                ];

                $stats[] = $class::whereBetween('created_at', $between)->count();

                if ($date->format('Y-m-d') === $moment->format('Y-m-d')) {
                    break;
                }

                $date->modify('+1 day');
            }
        }

        return $stats;
    }

    private function getCurrentWeek(DateTimeImmutable $current) : array
    {
        $week = [];

        $datetime = new DateTime();
        $datetime->setISODate($current->format('Y'), $current->format('W'));
        $week[] = $datetime->format('Y-m-d');
        $datetime->modify('+6 days');
        $week[] = $datetime->format('Y-m-d');

        return $week;
    }

    protected function getStatsByMonth(string $class, array $period) : array
    {
        $stats = [];
        $moment = new DateTimeImmutable('now');
        if ( isset($period['today']) ) {
            unset($period['today']);
            for ($i = 0; $i < count($period); ++$i)
            {
                if ($i > 0) {
                    $moment = $moment->modify('-1 month');
                }

                $year = $moment->format('Y');
                $month = $moment->format('m');
                $between = [
                    $year .'-'. $month .'-01 00:00:00',
                    $year .'-'. $month .'-'. $moment->format('t') .' 23:59:59'
                ];

                $stats[] = $class::whereBetween('created_at', $between)->count();
            }

            $stats = array_reverse($stats);

        } else {
            $currentMonth = $moment->format('F');
            $currentYear = $moment->format('Y');
            for ($i = 0; $i < count($period); ++$i)
            {
                $month = (new DateTime($period[$i]))->format('m');
                $between = [
                    $currentYear .'-'. $month .'-01 00:00:00',
                    $currentYear .'-'. $month .'-'. $moment->format('t') .' 23:59:59'
                ];

                $stats[] = $class::whereBetween('created_at', $between)->count();

                if ($currentMonth === $period[$i]) {
                    break;
                }
            }
        }

        return $stats;
    }
}
