<?php

namespace App\Http\Controllers;

use App\Exports\ExportAnalytics;
use App\Card;
use App\Analytics;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\DataTables;

class AnalyticsController extends Controller {
	public function __construct() {
		$this->middleware('role:admin,staff');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$analytics = Analytics::all();
		return view('analytics.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required',
			'alamat' => 'required',
			'email' => 'required|unique:analytics',
			'telepon' => 'required',
		]);

		Analytics::create($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Analytics Created',
		]);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$analytics = Analytics::find($id);
		return $analytics;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$this->validate($request, [
			'name' => 'required|string|min:2',
			'alamat' => 'required|string|min:2',
			'email' => 'required|string|email|max:255|unique:Analytics',
			'telepon' => 'required|string|min:2',
		]);

		$analytics = Analytics::findOrFail($id);

		$analytics->update($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Analytics Updated',
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		Analytics::destroy($id);

		return response()->json([
			'success' => true,
			'message' => 'Analytics Delete',
		]);
	}

	public function apiAnalytics() {
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
		return Datatables::of($datas)
			->addColumn('action', function ($datas) {
				return '<a onclick="editForm(' . $datas->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
				'<a onclick="deleteData(' . $datas->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			})
			->rawColumns(['action'])->make(true);
	}

	public function ImportPDF(Request $request) {
		//Validasi
		$this->validate($request, [
			'file' => 'required|mimes:pdf',
		]);

		if ($request->hasFile('file')) {

			$currentDateTime = date('Y-m-d-H-i-s');
			// Generate the file name
			$fileName = 'card_' . $currentDateTime.'.'.$request->file->getClientOriginalExtension();

			
			$file_url = './upload/pdfs/'.$fileName;
            $request->file->move(public_path('/upload/pdfs/'), $file_url);

			$command = "python ".public_path('upload/pdfs/scrappingData.py')." ".$file_url;

			exec($command, $output, $return_var);

			$validJsonString = str_replace("'", '"', $output[0]);
			$outputArray = json_decode($validJsonString);

			$real_hours = array(
				"1" => "09:00-10:00",
				"2" => "10:00-11:00",
				"3" => "11:00-12:00",
				"4" => "12:00-13:00",
				"5" => "13:00-14:00",
				"6" => "14:00-15:00",
				"7" => "15:00-16:00",
				"8" => "16:00-17:00",
				"9" => "17:00-18:00",
				"10" => "18:00-19:00",
				"11" => "19:00-20:00",
				"12" => "20:00-21:00",
				"13" => "21:00-22:00",
			);

			$card = Card::where('number',$outputArray->number)->first();

			$x=0; 
			$y=0; 
			$z=0; 
			
			$working_days =  explode(",", $card->working_days);
			$hours = explode(",", $card->usage_hours);
			$bus_lines = $card->bus_lines;

			$usage_hours = [];

			foreach ($outputArray->datas as $data){

				if (in_array($data->working_day, $working_days)) {
					// echo "The date " . $data->working_day . " is present in the array.\n". PHP_EOL;
				} else {
					$x++;

					// X Variable
					// echo "The date " . $data->working_day . " is not present in the array.\n". PHP_EOL;      
				}
			
			
				foreach ($hours as $key => $value) {
					if (array_key_exists($value, $real_hours)) {
						$usage_hours [] = $real_hours[$value];
					}
				}
				
				// $time = "10:54:31";
				$time = $data->working_hour;
				
				$is_time_found = false;
				foreach ($usage_hours as $hour_range) {
					$start_time = explode("-", $hour_range)[0];
					$end_time = explode("-", $hour_range)[1];
				
					$start_time_parts = explode(":", $start_time);
					$end_time_parts = explode(":", $end_time);
				
					$start_time_seconds = ($start_time_parts[0] * 3600) + ($start_time_parts[1] * 60);
					$end_time_seconds = ($end_time_parts[0] * 3600) + ($end_time_parts[1] * 60);
				
					$time_parts = explode(":", $time);
					$time_seconds = ($time_parts[0] * 3600) + ($time_parts[1] * 60) + $time_parts[2];
				
					if ($time_seconds >= $start_time_seconds && $time_seconds <= $end_time_seconds) {
						$is_time_found = true;
						// echo "The time $time is within the range " . $hour_range . ".\n";
						break;
					}
				}
				
				if (!$is_time_found) {
					// Y Variable
					$y++;
					// echo "The time $time is not within any of the ranges in the \$usage_hours array.\n";
				}
				$compareBusLines = $data->bus_line;
				if($bus_lines == $compareBusLines) {
					// echo "The Bus lines is same! \n";
				} else {
					// Z Variable
					// echo "The Bus lines not contains! \n";
					$z++;
				}
			}




			// $cards = Card::all();


			// $x=0; 
			// $y=0; 
			// $z=0; 
			
			// foreach ($cards as $card){
			// 	$working_days =  explode(",", $card->working_days);
			// 	$hours = explode(",", $card->usage_hours);
			// 	$bus_lines = $card->bus_lines;

			// 	$usage_hours = [];

			// 	foreach ($outputArray->datas as $data){
	
			// 		if (in_array($data->working_day, $working_days)) {
			// 			// echo "The date " . $data->working_day . " is present in the array.\n". PHP_EOL;
			// 		} else {
			// 			$x++;
	
			// 			// X Variable
			// 			// echo "The date " . $data->working_day . " is not present in the array.\n". PHP_EOL;      
			// 		}
	
				
				
			// 	foreach ($hours as $key => $value) {
			// 		if (array_key_exists($value, $real_hours)) {
			// 			$usage_hours [] = $real_hours[$value];
			// 		}
			// 	}
				
			// 	// $time = "10:54:31";
			// 	$time = $data->working_hour;

				
			// 	$is_time_found = false;
			// 	foreach ($usage_hours as $hour_range) {
			// 		$start_time = explode("-", $hour_range)[0];
			// 		$end_time = explode("-", $hour_range)[1];
				
			// 		$start_time_parts = explode(":", $start_time);
			// 		$end_time_parts = explode(":", $end_time);
				
			// 		$start_time_seconds = ($start_time_parts[0] * 3600) + ($start_time_parts[1] * 60);
			// 		$end_time_seconds = ($end_time_parts[0] * 3600) + ($end_time_parts[1] * 60);
				
			// 		$time_parts = explode(":", $time);
			// 		$time_seconds = ($time_parts[0] * 3600) + ($time_parts[1] * 60) + $time_parts[2];
				
			// 		if ($time_seconds >= $start_time_seconds && $time_seconds <= $end_time_seconds) {
			// 			$is_time_found = true;
			// 			// echo "The time $time is within the range " . $hour_range . ".\n";
			// 			break;
			// 		}
			// 	}
				
			// 	if (!$is_time_found) {
			// 		// Y Variable
			// 		$y++;
			// 		// echo "The time $time is not within any of the ranges in the \$usage_hours array.\n";
			// 	}
			// 	$compareBusLines = $data->bus_line;
			// 	if($bus_lines == $compareBusLines) {
			// 		// echo "The Bus lines is same! \n";
			// 	} else {
			// 		// Z Variable
			// 		// echo "The Bus lines not contains! \n";
			// 		$z++;
			// 	}
			// 	}
			// }

			// dd($x,$y,$z);
			// dd($output);

			$data = [
				'card_id' => $card->id,
				'non_working_days' => $x,
				'non_working_hours' => $y,
				'non_bus_lines' => $z,
				'file_url' => $file_url,
			];
			// dd($data);

			Analytics::create($data);

			// return response()->json([
			// 	'success' => true,
			// 	'message' => 'Analytics Created',
			// ]);


			return redirect()->back()->with(['success' => 'Upload file pdf datas !']);
		}

		return redirect()->back()->with(['error' => 'Please choose file before!']);
	}

	public function exportAnalyticsAll() {
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
		$pdf = PDF::loadView('analytics.analyticsAllPDF', compact('datas'));
		return $pdf->download('analytics.pdf');
	}

	public function exportExcel() {
		return (new ExportAnalytics)->download('analytics.xlsx');
	}
}
