<?php


namespace App\Services;


use App\Company;
use Illuminate\Database\Eloquent\Collection;

class AuthUser
{
    protected $user;
    /**
     * @var Company
     */
    protected $selectedCompany;
    /**
     * @var Collection
     */
    protected $companies;

    protected static $instance;

    public function __construct()
    {
        $this->user = \Auth::user();

        if ($companyId = session()->get('companyId')) {
            $this->setCompany($companyId);
        }
    }

    public static function instance(): AuthUser{
        if(!static::$instance){
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function user(){
        if (!$this->user || (\Auth::check() && ($this->user->id ?? null) !== \Auth::id())) {
            $this->user = \Auth::user();
        }
        return $this->user;
    }

    public function userId(){
        return $this->user()->id ?? null;
    }

    public function userEmail(){
        return $this->user()->email ?? null;
    }

    public function userName(){
        return $this->user()->name ?? null;
    }

    public function setCompany($id)
    {
        $user = $this->user();
        if (!$user) {
            return;
        }

        if (session()->get('companyId') == $id && ($this->selectedCompany->id ?? null) == $id) {
            return;
        }

        $query = $this->isAdmin() ? Company::where('id', $id) : $user->companies()->where('id', $id);

        if ($this->selectedCompany = $query->first()) {
            session()->put('companyId', $this->selectedCompany->id);
            session()->save();
        }
    }

    public function clearCompany(): void
    {
        $this->selectedCompany = null;
        session()->forget('companyId');
        session()->save();
    }

    public function companies(): ?Collection
    {
        $user = $this->user();
        if (!$user) {
            return null;
        }

        if ($this->isAdmin()) {
            return Company::orderBy('title', 'asc')->get();
        }

        return $user->companies()->orderBy('title', 'asc')->get();
    }

    public function isLoggedIn(): bool
    {
        return boolval($this->user());
    }

    public function selectedCompany(): ?Company
    {
        $this->user();
        $sessCompanyId = session()->get('companyId');
        if ($sessCompanyId && (!$this->selectedCompany || $this->selectedCompany->id != $sessCompanyId)) {
            $this->setCompany($sessCompanyId);
        }
        return $this->selectedCompany;
    }

    public function selectedCompanyId(): ? int
    {
       return $this->selectedCompany()->id ?? null;
    }

    public function isAdmin(): bool
    {
        $user = $this->user();
        return boolval($user && $user->isAdmin());
    }

}