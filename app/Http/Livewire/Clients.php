<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class Clients extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $user_id, $client_odoo_id, $name, $contact, $email, $phone;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.clients.view', [
            'clients' => Client::latest()
						->orWhere('user_id', 'LIKE', $keyWord)
						->orWhere('client_odoo_id', 'LIKE', $keyWord)
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('contact', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
						->orWhere('phone', 'LIKE', $keyWord)
						->paginate(50),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->user_id = null;
		$this->client_odoo_id = null;
		$this->name = null;
		$this->contact = null;
		$this->email = null;
		$this->phone = null;
    }

    public function store()
    {
        $this->validate([
		'client_odoo_id' => 'required',
		'name' => 'required',
		'contact' => 'required',
		'email' => 'required',
		'phone' => 'required',
        ]);

        Client::create([
			'user_id' => $this-> user_id,
			'client_odoo_id' => $this-> client_odoo_id,
			'name' => $this-> name,
			'contact' => $this-> contact,
			'email' => $this-> email,
			'phone' => $this-> phone
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Client Successfully created.');
    }

    public function edit($id)
    {
        $record = Client::findOrFail($id);

        $this->selected_id = $id;
		$this->user_id = $record-> user_id;
		$this->client_odoo_id = $record-> client_odoo_id;
		$this->name = $record-> name;
		$this->contact = $record-> contact;
		$this->email = $record-> email;
		$this->phone = $record-> phone;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'client_odoo_id' => 'required',
		'name' => 'required',
		'contact' => 'required',
		'email' => 'required',
		'phone' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Client::find($this->selected_id);
            $record->update([
			'user_id' => $this-> user_id,
			'client_odoo_id' => $this-> client_odoo_id,
			'name' => $this-> name,
			'contact' => $this-> contact,
			'email' => $this-> email,
			'phone' => $this-> phone
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Client Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Client::where('id', $id);
            $record->delete();
        }
    }
}
