<?php

namespace App\Exports;

use App\Analytics;
use App\Card;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExportAnalytics implements FromView
{
    /**
     * melakukan format dokumen menggunakan html, maka package ini juga menyediakan fungsi lainnya agar dapat me-load data tersebut dari file html / blade di Laravel
     */
    use Exportable;

    public function view(): View
    {
        // TODO: Implement view() method.


        $analytics = Analytics::all();
		$datas = [];
		foreach($analytics as $analytic) {
			$datas[] = (object) [
				'id'=> $analytic->id,
				'number' => Card::find($analytic->card_id)->number,
				'non_working_days' => $analytic->non_working_days,
				'non_working_hours' => $analytic->non_working_hours,
				'non_bus_lines' => $analytic->non_bus_lines,
				'file_url' => $analytic->file_url,
			];
		}


        return view('analytics.AnalyticsAllExcel',[
            'datas' => $datas
        ]);
    }
}
