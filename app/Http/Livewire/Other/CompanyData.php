<?php

namespace App\Http\Livewire\Other;

use Livewire\Component;
use Illuminate\View\View;
use App\CompanyVatNumber;
use App\Services\AuthUser;

class CompanyData extends Component
{
    const DUMMY_ID_PREFIX = 'x_';

    public $details = [
        'title',
        'address',
        'registration_number',
        'bank',
        'swift',
        'account_number',
        'vat_numbers' => [],
    ];

    public $emptyVatLines = [];

    private $company;
    private $companyId;

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


    public function render() : View
    {
        $this->read();

        return view('livewire.other.company-data');
    }

    public function read() : void
    {
        $this->details['title']               = $this->company->title;
        $this->details['address']             = $this->company->address;
        $this->details['registration_number'] = $this->company->registration_number;
        $this->details['bank']                = $this->company->bank;
        $this->details['swift']               = $this->company->swift;
        $this->details['account_number']      = $this->company->account_number;
    }

    public function addVatLine()
    {
        $id = self::DUMMY_ID_PREFIX . uniqid();

        $this->details['vat_numbers'][$id] = '';
    }


    public function removeVatLine($id)
    {
        if (isset($this->details['vat_numbers'][$id])) {
            unset($this->details['vat_numbers'][$id]);
        }
    }

    public function save()
    {
        $this->company->title               = $this->details['title'];
        $this->company->address             = $this->details['address'];
        $this->company->registration_number = $this->details['registration_number'];
        $this->company->bank                = $this->details['bank'];
        $this->company->swift               = $this->details['swift'];
        $this->company->account_number      = $this->details['account_number'];
        $this->company->save();


        $oldVatIds = $this->company->vatNumbers->pluck('id',
            'id')
            ->toArray();

        foreach ($this->details['vat_numbers'] as $id => $val) {

            if (empty($val)) {
                continue;
            }

            if (
            $vatObject = $this->company->vatNumbers->where('id',
                $id)
                ->first()
            ) {

                if (isset($oldVatIds[$vatObject->id])) {
                    unset($oldVatIds[$vatObject->id]);
                }
            } else {
                $vatObject             = new CompanyVatNumber;
                $vatObject->company_id = $this->companyId;
            }

            $vatObject->vat_number = $val;
            $vatObject->save();
        }

        foreach ($oldVatIds as $id) {
            CompanyVatNumber::where('company_id',
                $this->companyId)
                ->where('id',
                    $id)
                ->delete();
        }


        session()->flash('message',
            'Data successfully updated.');

        $this->dispatchBrowserEvent('alert_remove');
    }

    public function mount()
    {
        $this->company->vatNumbers->each(function ($vat)
        {
            $this->details['vat_numbers'][$vat->id] = $vat->vat_number;
        });

    }
}