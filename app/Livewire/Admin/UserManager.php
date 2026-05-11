<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $userId, $name, $nik, $no_wa, $email, $role, $password, $password_confirmation;
    public $isEdit = false;
    public $search = '';
    public $showDeleted = false;
    public $showModal = false;

    public function mount()
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'superadmin']), 403);
    }

    public function render()
    {
        $query = User::query();

        if ($this->showDeleted) {
            $query->onlyTrashed();
        }

        $query->where(function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('nik', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%');
        });

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.user-manager', [
            'users' => $users
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,nik',
            'no_wa' => 'required|numeric|min_digits:10|max_digits:15',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'role' => [
                'required',
                Rule::in(auth()->user()->role === 'superadmin' ? ['admin', 'warga', 'petugas', 'superadmin'] : ['admin', 'warga', 'petugas'])
            ],
            'password' => 'required|string|min:8|confirmed|regex:/[a-zA-Z]/|regex:/[0-9]/',
        ]);

        User::create([
            'name' => strtoupper($this->name),
            'nik' => $this->nik,
            'no_wa' => $this->no_wa,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'Pengguna berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->nik = $user->nik;
        $this->no_wa = $user->no_wa;
        $this->email = $user->email;
        $this->role = $user->role;
        // Do not load password for editing
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function update()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'nik' => ['required', 'numeric', 'digits:16', Rule::unique('users')->ignore($this->userId)],
            'no_wa' => 'required|numeric|min_digits:10|max_digits:15',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'role' => [
                'required',
                Rule::in(auth()->user()->role === 'superadmin' ? ['admin', 'warga', 'petugas', 'superadmin'] : ['admin', 'warga', 'petugas'])
            ],
        ];

        // Only validate password if it's filled
        if (!empty($this->password)) {
            $rules['password'] = 'required|string|min:8|confirmed|regex:/[a-zA-Z]/|regex:/[0-9]/';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);

        $updateData = [
            'name' => strtoupper($this->name),
            'nik' => $this->nik,
            'no_wa' => $this->no_wa,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        session()->flash('success', 'Pengguna berhasil diperbarui.');
        $this->closeModal();
    }

    public function delete($id)
    {
        if (auth()->id() == $id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
    }

    public function forceDelete($id)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);
        
        if (auth()->id() == $id) {
            session()->flash('error', 'Anda tidak dapat menghapus permanen akun Anda sendiri.');
            return;
        }

        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        
        session()->flash('success', 'Pengguna berhasil dihapus permanen dari sistem.');
    }

    public function impersonate($id)
    {
        abort_unless(auth()->user()->role === 'superadmin', 403);

        if (auth()->id() == $id) {
            session()->flash('error', 'Anda sudah masuk sebagai akun ini.');
            return;
        }

        session()->put('impersonator_id', auth()->id());
        auth()->loginUsingId($id);

        return redirect()->to('/');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        session()->flash('success', 'Akun pengguna berhasil dipulihkan.');
    }

    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        $user->markEmailAsVerified();
        session()->flash('success', 'Email pengguna berhasil diverifikasi manual.');
    }

    public function closeModal()

    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->nik = '';
        $this->no_wa = '';
        $this->email = '';
        $this->role = 'warga'; // default
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }
}