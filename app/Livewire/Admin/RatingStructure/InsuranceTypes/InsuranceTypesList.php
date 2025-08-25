<?php

namespace App\Livewire\Admin\RatingStructure\InsuranceTypes;

use Livewire\Component;
use App\Models\InsuranceType;

class InsuranceTypesList extends Component
{
    public $types = [];

    protected $listeners = [
        'refreshInsuranceTypes' => 'loadTypes',
        'orderInsuranceType' => 'handleOrderInsuranceType'
    ];

    public function mount()
    {
        $this->loadTypes();
    }

    public function loadTypes()
    {
        $this->types = InsuranceType::orderBy('order_column')->withCount('insurances')->get();
    }

    public function handleOrderInsuranceType($item, $position)
    {
        if (!isset($item, $position)) {
            return;
        }

        $movedType = InsuranceType::find($item['id']);
        if (!$movedType) {
            return;
        }

        $newPosition = (int) $position;

        $types = InsuranceType::orderBy('order_column')->get();
        $filteredTypes = $types->reject(fn ($t) => $t->id == $movedType->id)->values();

        $newOrder = collect();
        foreach ($filteredTypes as $index => $type) {
            if ($index == $newPosition) {
                $newOrder->push($movedType);
            }
            $newOrder->push($type);
        }

        if ($newPosition >= $filteredTypes->count()) {
            $newOrder->push($movedType);
        }

        foreach ($newOrder as $index => $type) {
            InsuranceType::where('id', $type->id)->update(['order_column' => $index]);
        }

        $this->loadTypes();
    }

    public function deleteInsuranceType($id)
    {
        InsuranceType::findOrFail($id)->delete();
        $this->loadTypes();
    }

    public function toggleActive($id)
    {
        $insuranceType = InsuranceType::findOrFail($id);
        $insuranceType->update(['is_active' => !$insuranceType->is_active]);
        $this->loadTypes();
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.insurance-types.insurance-types-list');
    }
}