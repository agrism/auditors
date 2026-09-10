<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfile extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $successMessage = '';

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
        }
    }

    public function rules()
    {
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vārds un uzvārds ir obligāts lauks.',
            'email.required' => 'E-pasta adrese ir obligāts lauks.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
            'email.unique' => 'Šāda e-pasta adrese jau ir reģistrēta.',
            'password.min' => 'Parolei jābūt vismaz 6 simbolus garai.',
            'password.confirmed' => 'Ievadītās paroles nesakrīt.',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->password = '';
        $this->password_confirmation = '';
        $this->successMessage = 'Profila dati veiksmīgi saglabāti!';
    }

    public function render()
    {
        return view('livewire.user-profile', [
            'user' => Auth::user(),
        ]);
    }
}
