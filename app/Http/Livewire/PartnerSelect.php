<?php

namespace App\Http\Livewire;

use App\Company;
use App\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PartnerSelect extends Component
{

    /**
     * @var Collection
     */
	public  $partners;
	public $selectedPartnerId;
	public $selectedPartnerName;
	public $selectedPartnerRegNo;
	public $selectedPartnerVatNo;
	public $selectedPartnerAddress;

	private $company;
	private int $companyId;

	public function __construct($id = null)
	{
		parent::__construct($id);
		$this->company = app()->Company;

		if (request()->session()->has('companyId')) {
			$this->companyId = request()->session()->get('companyId');

			if (!$this->company = Company::where('id', $this->companyId)->first()) {
				$this->companyId = null;
			}
		}
	}

	public function edit($id = null){
		$partner = Partner::where('company_id', $this->companyId)->whereId($id)->first();

		$this->selectedPartnerId = $partner->id ?? '';
		$this->selectedPartnerName = $partner->name ?? '';
		$this->selectedPartnerRegNo = $partner->registration_number ?? '';
		$this->selectedPartnerVatNo = $partner->vat_number ?? '';
		$this->selectedPartnerAddress = $partner->address ?? '';
	}

	public function save(){

		$this->validate([
			'selectedPartnerName' => 'required',
			'selectedPartnerRegNo' => 'required',
		]);

		Log::debug('save');
		if(!$partner = Partner::whereId($this->selectedPartnerId)->whereCompanyId($this->companyId)->first()){
			$partner = new Partner;
			$partner->company_id = $this->companyId;
		}
		$partner->name = $this->selectedPartnerName;
		$partner->registration_number = $this->selectedPartnerRegNo;
		$partner->vat_number = $this->selectedPartnerVatNo;
		$partner->address = $this->selectedPartnerAddress;
		$partner->save();

		$this->selectedPartnerId = $partner->id;
		$this->partners = $this->partners();

		$this->emit('modalSave');
	}


	public function mount(){
		$this->partners = $this->partners();
	}

    public function render()
    {
        return view('livewire.partner-select');
    }

    private function partners(){
		$partners =  Partner::where('company_id', $this->companyId)
			->orderBy('name', 'asc')
			->get()->map(function($p){
			return [
				'name' => $p->name,
				'id' => $p->id,
			];
		});

		$blank = new Partner;
		$blank->name = '-';
		$blank->id = null;
		$partners->prepend([
			'name' => '-',
			'id' => null,
		]);

		return $partners;
	}
}
