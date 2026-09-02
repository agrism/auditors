<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Exports\WorkingHours;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Validator;


class WorkingHoursController extends Controller
{

	private $dates = [];
	private $dataArray = [];
	private $i = 0;


	public function index(Request $request)
	{
		$data = [];
		$companies = Company::orderBy('title', 'asc')->get();

		$latvianMonths = [
			'01' => 'Janvāris',
			'02' => 'Februāris',
			'03' => 'Marts',
			'04' => 'Aprīlis',
			'05' => 'Maijs',
			'06' => 'Jūnijs',
			'07' => 'Jūlijs',
			'08' => 'Augusts',
			'09' => 'Septembris',
			'10' => 'Oktobris',
			'11' => 'Novembris',
			'12' => 'Decembris',
		];

		$currentYear = (int) Carbon::now()->format('Y');
		$years = [];
		for ($y = $currentYear - 3; $y <= $currentYear + 1; $y++) {
			$years[(string)$y] = (string)$y;
		}

		$selectedMonth = Carbon::now()->subMonth()->format('m');
		$selectedYear = Carbon::now()->subMonth()->format('Y');

		$months = collect($latvianMonths);

		return view('admin.working-hours.index',
			compact('data', 'companies', 'months', 'years', 'selectedMonth', 'selectedYear'));
	}


	public function handle(Request $request)
	{
		$company = Company::with('employees')->whereId($request->get('company_id'))->first();

		return \Maatwebsite\Excel\Facades\Excel::download(
			new WorkingHours($company, $request->get('month'), $request->get('year')),
			'tabele-'.$company->title.'_'.$request->get('month').'_'.$request->get('year').'_tuksa.xlsx');
	}

}