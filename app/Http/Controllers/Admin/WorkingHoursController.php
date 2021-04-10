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

		$period = CarbonPeriod::create(Carbon::now()->startOfMonth()->startOfYear()->format('Y-m-d'),
			'1 month',
			Carbon::now()->endOfMonth()->endOfYear()->format('Y-m-d'));

		$monthsData = [];
		$yearsData = [];

		foreach ($period as $date) {
			$monthsData[$date->format('m')] = [
				'key' => $date->format('m'),
				'value' => $date->format('F'),
				'selected' => Carbon::now()->subMonth()->format('m') === $date->format('m'),
			];
			$yearsData[$date->format('Y')] = [
				'key' => $date->format('Y'),
				'value' => $date->format('Y'),
				'selected' => Carbon::now()->subMonth()->format('Y') === $date->format('Y'),
			];
		}

		$months = collect($monthsData)->pluck('value', 'key');
		$years = collect($yearsData)->pluck('value', 'key');
		$selectedMonth = collect($monthsData)->filter(function ($month) {
				return $month['selected'] === true;
			})->first()['key'] ?? false;

		$selectedYear = collect($yearsData)->filter(function ($year) {
				return $year['selected'] === true;
			})->first()['key'] ?? false;

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