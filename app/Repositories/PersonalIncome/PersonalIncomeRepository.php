<?php namespace App\Repositories\PersonalIncome;

interface PersonalIncomeRepository
{

    public function getPersonalIncomes(array $params);

    public function getPartners();

    public function getPersonalIncomeTypes();

    public function create();


}
