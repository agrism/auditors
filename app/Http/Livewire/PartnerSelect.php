<?php

namespace App\Http\Livewire;

use App\Company;
use App\Partner;
use App\Services\AuthUser;
use Illuminate\Support\Collection;
use Livewire\Component;

class PartnerSelect extends Component
{

    /**
     * @var Collection
     */
	public $partners;
	public $selectedPartnerId;
	public $selectedPartnerName;
	public $selectedPartnerRegNo;
	public $selectedPartnerVatNo;
	public $selectedPartnerAddress;
	public $selectedPartnerBank;
	public $selectedPartnerSwift;
	public $selectedPartnerAccountNumber;

	private $company;
	private int $companyId;

	public function __construct($id = null)
	{
		parent::__construct($id);

        if(!AuthUser::instance()->isLoggedIn()){
            return redirect('/');
        }

        if(!$this->company = AuthUser::instance()->selectedCompany()){
            return redirect('/');
        }

        if(!$this->companyId = AuthUser::instance()->selectedCompanyId()){
            return redirect('/');
        }
	}

	public function edit($id = null){
		$partner = Partner::where('company_id', $this->companyId)->whereId($id)->first();

		$this->selectedPartnerId = $partner->id ?? '';
		$this->selectedPartnerName = $partner->name ?? '';
		$this->selectedPartnerRegNo = $partner->registration_number ?? '';
		$this->selectedPartnerVatNo = $partner->vat_number ?? '';
		$this->selectedPartnerAddress = $partner->address ?? '';
		$this->selectedPartnerBank = $partner->bank ?? '';
		$this->selectedPartnerSwift = $partner->swift ?? '';
		$this->selectedPartnerAccountNumber = $partner->account_number ?? '';

		$this->dispatchBrowserEvent('partner_modal_open');
	}

	public function cancel(){
	    $this->dispatchBrowserEvent('partner_modal_close');
    }

	public function save(){

		$this->validate([
			'selectedPartnerName' => 'required',
			'selectedPartnerRegNo' => 'required',
		]);

		if(!$partner = Partner::whereId($this->selectedPartnerId)->whereCompanyId($this->companyId)->first()){
			$partner = new Partner;
			$partner->company_id = $this->companyId;
		}
		$partner->name = $this->selectedPartnerName;
		$partner->registration_number = $this->selectedPartnerRegNo;
		$partner->vat_number = $this->selectedPartnerVatNo;
		$partner->address = $this->selectedPartnerAddress;
		$partner->bank = $this->selectedPartnerBank;
		$partner->swift = $this->selectedPartnerSwift;
		$partner->account_number = $this->selectedPartnerAccountNumber;
		$partner->save();

		$this->selectedPartnerId = $partner->id;
		$this->partners = $this->partners();

		$this->dispatchBrowserEvent('partner_modal_close');
	}

	public function updatedSelectedPartnerId(){
        $this->emit('updatedSelectedId', $this->selectedPartnerId);
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
