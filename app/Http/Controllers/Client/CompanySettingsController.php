<?php

namespace App\Http\Controllers\Client;


use App\CompanySetting;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class CompanySettingsController extends Controller
{

	public function index(): View
	{
		$existingSettings = CompanySetting::where('company_id', $this->company['id'])->get();

		$settings = new Collection;

		collect(CompanySetting::getConstants())->each(function ($settingKey) use ($existingSettings, &$settings) {
			if($s = $existingSettings->filter(function ($existingSetting) use($settingKey) {
				return $existingSetting->variable === $settingKey;
			})->first()){
				$settings->push($s);
			} else {
				$settings->push((object)[
					'variable' => $settingKey,
					'content' => null,
				]);
			}
		});

		$company = $this->company;

		return view('client.companies.settings.index', compact('settings', 'company'));
	}


	/**
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request): \Illuminate\Http\RedirectResponse
	{
		$oldSettings = CompanySetting::where('company_id', $this->company->id)->get();

		$dataToSave = [];

		foreach ($request->all() as $key => $value){
			if(in_array($key, CompanySetting::getConstants())){
				$dataToSave[] = [
					'variable' => $key,
					'content' => $value,
					'company_id' => $this->company['id'],
				];
			}
		}

		if(CompanySetting::insert($dataToSave) !== true){
			return redirect()->back()->withInput()->withErrors(['msg' => 'Error saving data']);
		}

		if(CompanySetting::whereIn('id', $oldSettings->pluck('id')->all())->delete() !== $oldSettings->count()){
			return redirect()->back()->withInput()->withErrors(['msg' => 'Error deleting old data']);
		}

		return redirect()->route('client.companies.settings.index');
	}
}
