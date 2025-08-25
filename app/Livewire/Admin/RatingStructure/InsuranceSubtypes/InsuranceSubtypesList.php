<?php

namespace App\Livewire\Admin\RatingStructure\InsuranceSubtypes;

use Livewire\Component;
use App\Models\InsuranceSubtype;

class InsuranceSubtypesList extends Component
{
    public $subtypes = [];

    protected $listeners = [
        'refreshInsuranceSubtypes' => 'loadSubtypes',
        'orderInsuranceSubtype' => 'handleOrderInsuranceSubtype'
    ];

    public function mount()
    {
        $this->loadSubtypes();
    }

    public function loadSubtypes()
    {
        $this->subtypes = InsuranceSubtype::orderBy('order_column')->get();
    }

    public function handleOrderInsuranceSubtype($item, $position)
    {
        if (!isset($item, $position)) {
            return;
        }

        $movedSubtype = InsuranceSubtype::find($item['id']);
        if (!$movedSubtype) {
            return;
        }

        $newPosition = (int) $position;

        $subtypes = InsuranceSubtype::orderBy('order_column')->get();
        $filteredSubtypes = $subtypes->reject(fn ($s) => $s->id == $movedSubtype->id)->values();

        $newOrder = collect();
        foreach ($filteredSubtypes as $index => $subtype) {
            if ($index == $newPosition) {
                $newOrder->push($movedSubtype);
            }
            $newOrder->push($subtype);
        }

        if ($newPosition >= $filteredSubtypes->count()) {
            $newOrder->push($movedSubtype);
        }

        foreach ($newOrder as $index => $subtype) {
            InsuranceSubtype::where('id', $subtype->id)->update(['order_column' => $index]);
        }

        $this->loadSubtypes();
    }

    public function deleteInsuranceSubtype($id)
    {
        InsuranceSubtype::findOrFail($id)->delete();
        $this->loadSubtypes();
    }

    public function toggleActive($id)
    {
        $subtype = InsuranceSubtype::findOrFail($id);
        $subtype->update(['is_active' => !$subtype->is_active]);
        $this->loadSubtypes();
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.insurance-subtypes.insurance-subtypes-list');
    }
}
