<?php

namespace App\Http\Livewire\Selects;

use App\Partner;
use Livewire\Component;
use App\Services\AuthUser;
use Illuminate\Support\Collection;

class PartnerSelectV2 extends Component
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

    private     $company;
    private int $companyId;

    public function __construct($id = null)
    {
        parent::__construct($id);

        if ( !AuthUser::instance()
            ->isLoggedIn()
        ) {
            return redirect('/');
        }

        if ( !$this->company = AuthUser::instance()
            ->selectedCompany()
        ) {
            return redirect('/');
        }

        if ( !$this->companyId = AuthUser::instance()
            ->selectedCompanyId()
        ) {
            return redirect('/');
        }
    }

    public function edit($id = null)
    {
        $partner = Partner::where('company_id',
            $this->companyId)
            ->whereId($id)
            ->first();

        $this->selectedPartnerId            = $partner->id ?? '';
        $this->selectedPartnerName          = $partner->name ?? '';
        $this->selectedPartnerRegNo         = $partner->registration_number ?? '';
        $this->selectedPartnerVatNo         = $partner->vat_number ?? '';
        $this->selectedPartnerAddress       = $partner->address ?? '';
        $this->selectedPartnerBank          = $partner->bank ?? '';
        $this->selectedPartnerSwift         = $partner->swift ?? '';
        $this->selectedPartnerAccountNumber = $partner->account_number ?? '';

        $this->dispatchBrowserEvent('partner_modal_open');
    }

    public function cancel()
    {
        $this->dispatchBrowserEvent('partner_modal_close');
    }

    public function save()
    {

        $this->validate([
            'selectedPartnerName'  => [
                'required',
                function (
                    $attribute,
                    $value,
                    $fail
                ) {
                    if (
                    Partner::whereName($value)
                        ->where('company_id',
                            $this->companyId)
                        ->where('id',
                            '!=',
                            $this->selectedPartnerId)
                        ->first()
                    ) {
                        $fail('Partner with name "' . $value . '" is exists');
                    }
                },
            ],
            'selectedPartnerRegNo' => [
                'required',
                function (
                    $attribute,
                    $value,
                    $fail
                ) {

                    $value = preg_replace('/\s+/','',$value);

                    if (
                    $existingPartner = Partner::where('registration_number',
                        $value)
                        ->where('company_id',
                            $this->companyId)
                        ->where('id',
                            '!=',
                            $this->selectedPartnerId)
                        ->first()
                    ) {
                        $fail('Registration number "' . $value . '" is used for other Partner: "' . $existingPartner->name . '"');
                    }
                },
            ],
            'selectedPartnerVatNo' => [
                function (
                    $attribute,
                    $value,
                    $fail
                ) {

                    $value = preg_replace('/\s+/','',$value);

                    if (!$value) {
                        return;
                    }

                    if (
                    $existingPartner = Partner::where('vat_number',
                        $value)
                        ->where('company_id',
                            $this->companyId)
                        ->where('id',
                            '!=',
                            $this->selectedPartnerId)
                        ->first()
                    ) {
                        $fail('VAT number "' . $value . '" is used for other Partner: "' . $existingPartner->name . '"');
                    }
                },
            ],
        ]);

        if ( !$partner = Partner::whereId($this->selectedPartnerId)
            ->whereCompanyId($this->companyId)
            ->first()
        ) {
            $partner             = new Partner;
            $partner->company_id = $this->companyId;
        }
        $partner->name                = $this->selectedPartnerName;
        $partner->registration_number = preg_replace('/\s+/','',$this->selectedPartnerRegNo);
        $partner->vat_number          = preg_replace('/\s+/','',$this->selectedPartnerVatNo) ;
        $partner->address             = $this->selectedPartnerAddress;
        $partner->bank                = $this->selectedPartnerBank;
        $partner->swift               = $this->selectedPartnerSwift;
        $partner->account_number      = $this->selectedPartnerAccountNumber;
        $partner->save();

        $this->selectedPartnerId = $partner->id;
        $this->partners          = $this->partners();

        $this->dispatchBrowserEvent('partner_modal_close');
        $this->updatedSelectedPartnerId();
    }

    private function partners()
    {
        $partners = Partner::where('company_id',
            $this->companyId)
            ->orderBy('name',
                'asc')
            ->get()
            ->map(function ($p)
            {
                return [
                    'label' => $p->name,
                    'value'   => $p->id,
                ];
            });

        $partner       = new Partner;
        $partner->label = '-';
        $partner->value   = null;
        $partners->prepend($partner);

        return $partners->toArray();
    }

    public function updatedSelectedPartnerId()
    {
        $this->emit('updatedSelectedId',
            $this->selectedPartnerId);
        $this->dispatchBrowserEvent('select_updated', ['newId'=> $this->selectedPartnerId]);

    }

    public function mount()
    {
        $this->partners = $this->partners();
    }

    public function render()
    {
        return view('livewire.selects.partner-select-v2');
    }
}