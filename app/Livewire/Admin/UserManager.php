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
    public $showModal = false;
    public $search = '';

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nik', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

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
            'role' => 'required|in:admin,petugas,warga',
            'password' => 'required|string|min:8|confirmed|regex:/[a-zA-Z]/|regex:/[0-9]/',
        ]);

        User::create([
            'name' => $this->name,
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
            'role' => 'required|in:admin,petugas,warga',
        ];

        // Only validate password if it's filled
        if (!empty($this->password)) {
            $rules['password'] = 'required|string|min:8|confirmed|regex:/[a-zA-Z]/|regex:/[0-9]/';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);

        $updateData = [
            'name' => $this->name,
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