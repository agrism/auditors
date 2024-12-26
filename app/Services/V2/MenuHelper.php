<?php

namespace App\Services\V2;

class MenuHelper
{
    use InstanceTrait;

    public function menu()
    {
        return [
            route('v2.companies.index') => 'Mani uzņēmumi',
            route('v2.invoices.index') => 'Rēķini',
            route('v2.partners.index') => 'Partneri',
        ];
    }
}
