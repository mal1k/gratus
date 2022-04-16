<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Receiver;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ReceiverChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        return Chartisan::build()
            ->labels(['Receiver'])
            ->dataset('prev month', [0])
            ->dataset('current month', [3]);
    }
}
