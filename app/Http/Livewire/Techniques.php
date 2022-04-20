<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Technique;

class Techniques extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $slug;
    public $updateMode = false;

    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return view('livewire.techniques.view', [
            'techniques' => Technique::latest()
                ->orWhere('nombre', 'LIKE', $keyWord)
                ->orWhere('slug', 'LIKE', $keyWord)
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
        $this->nombre = null;
        $this->slug = null;
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'slug' => 'required',
        ]);

        Technique::create([
            'nombre' => $this->nombre,
            'slug' => $this->slug
        ]);

        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Technique Successfully created.');
    }

    public function edit($id)
    {
        $record = Technique::findOrFail($id);

        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->slug = $record->slug;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'nombre' => 'required',
            'slug' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Technique::find($this->selected_id);
            $record->update([
                'nombre' => $this->nombre,
                'slug' => $this->slug
            ]);

            $this->resetInput();
            $this->updateMode = false;
            session()->flash('message', 'Technique Successfully updated.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Technique::where('id', $id);
            $record->delete();
        }
    }
}
