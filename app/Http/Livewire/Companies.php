<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class Companies extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $image, $manager, $email, $phone;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.companies.view', [
            'companies' => Company::latest()
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('image', 'LIKE', $keyWord)
						->orWhere('manager', 'LIKE', $keyWord)
						->orWhere('email', 'LIKE', $keyWord)
						->orWhere('phone', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
		$this->name = null;
		$this->image = null;
		$this->manager = null;
		$this->email = null;
		$this->phone = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'image' => 'required',
		'manager' => 'required',
		'email' => 'required',
		'phone' => 'required',
        ]);

        Company::create([
			'name' => $this-> name,
			'image' => $this-> image,
			'manager' => $this-> manager,
			'email' => $this-> email,
			'phone' => $this-> phone
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Company Successfully created.');
    }

    public function edit($id)
    {
        $record = Company::findOrFail($id);

        $this->selected_id = $id;
		$this->name = $record-> name;
		$this->image = $record-> image;
		$this->manager = $record-> manager;
		$this->email = $record-> email;
		$this->phone = $record-> phone;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'name' => 'required',
		'image' => 'required',
		'manager' => 'required',
		'email' => 'required',
		'phone' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Company::find($this->selected_id);
            $record->update([
			'name' => $this-> name,
			'image' => $this-> image,
			'manager' => $this-> manager,
			'email' => $this-> email,
			'phone' => $this-> phone
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Company Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Company::where('id', $id);
            $record->delete();
        }
    }
}
