<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tradename;

class Tradenames extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $client_id, $name;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('admin.tradenames.view', [
            'tradenames' => Tradename::latest()
						->orWhere('client_id', 'LIKE', $keyWord)
						->orWhere('name', 'LIKE', $keyWord)
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
		$this->client_id = null;
		$this->name = null;
    }

    public function store()
    {
        $this->validate([
		'client_id' => 'required',
		'name' => 'required',
        ]);

        Tradename::create([
			'client_id' => $this-> client_id,
			'name' => $this-> name
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Tradename Successfully created.');
    }

    public function edit($id)
    {
        $record = Tradename::findOrFail($id);

        $this->selected_id = $id;
		$this->client_id = $record-> client_id;
		$this->name = $record-> name;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'client_id' => 'required',
		'name' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Tradename::find($this->selected_id);
            $record->update([
			'client_id' => $this-> client_id,
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Tradename Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Tradename::where('id', $id);
            $record->delete();
        }
    }
}
