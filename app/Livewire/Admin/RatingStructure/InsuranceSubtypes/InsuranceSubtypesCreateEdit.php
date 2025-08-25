<?php

namespace App\Livewire\Admin\RatingStructure\InsuranceSubtypes;

use Livewire\Component;
use App\Models\InsuranceSubtype;
use App\Models\InsuranceType;
use Illuminate\Support\Str;

class InsuranceSubtypesCreateEdit extends Component
{
    public $showModal = false;
    public $insuranceSubtypeId;
    public $assignedInsuranceTypes = [];
    public $availableInsuranceTypes = [];
    public $insuranceTypeToAdd = null;
    public $insuranceSubtype;
    public $name;
    public $description;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean',
    ];

    protected $listeners = ['open-insurance-subtype-form' => 'open','reorderAssignedInsuranceTypes'];

    public function open($id = null)
    {
        $this->reset(['insuranceSubtypeId', 'name', 'description', 'is_active']);

        if ($id) {
            $subtype = InsuranceSubtype::findOrFail($id);
            $this->availableInsuranceTypes = InsuranceType::whereDoesntHave('insuranceSubtypes', function ($query) use ($id) {
                if ($id) {
                    $query->where('insurance_subtype_id', $id);
                }
            })->orderBy('name')->get();
            $this->assignedInsuranceTypes = $subtype->insuranceTypes
                ->map(fn($i) => ['id' => $i->id, 'name' => $i->name])
                ->values()
                ->toArray();
            $this->insuranceSubtypeId = $id;
            $this->name = $subtype->name;
            $this->description = $subtype->description;
            $this->is_active = $subtype->is_active;
        }

        $this->showModal = true;
    }
    public function addInsuranceType()
    {
        if ($this->insuranceTypeToAdd) {
            $insuranceType = InsuranceType::find($this->insuranceTypeToAdd);
            if ($insuranceType) {
                $this->assignedInsuranceTypes[] = ['id' => $insuranceType->id, 'name' => $insuranceType->name];
                $this->availableInsuranceTypes = $this->availableInsuranceTypes->where('id', '!=', $insuranceType->id);
                $this->insuranceTypeToAdd = null;
            }
        }
    }
    public function removeInsuranceType($insuranceTypeId)
    {
        $this->assignedInsuranceTypes = array_filter($this->assignedInsuranceTypes, function ($item) use ($insuranceTypeId) {
            return $item['id'] != $insuranceTypeId;
        });

        $insuranceType = InsuranceType::find($insuranceTypeId);
        if ($insuranceType) {
            $this->availableInsuranceTypes[] = ['id' => $insuranceType->id, 'name' => $insuranceType->name];
        }
    }
    public function reorderAssignedInsuranceTypes($item, $position)
    {
        if (!isset($item, $position)) {
            return;
        }

        $movedInsuranceType = InsuranceType::find($item['id']);
        if (!$movedInsuranceType) {
            return;
        }

        $newPosition = (int) $position;

        $insuranceTypes = InsuranceType::orderBy('order_column')->get();
        $filteredInsuranceTypes = $insuranceTypes->reject(fn ($i) => $i->id == $movedInsuranceType->id)->values();

        $newOrder = collect();
        foreach ($filteredInsuranceTypes as $index => $insuranceType) {
            if ($index == $newPosition) {
                $newOrder->push($movedInsuranceType);
            }
            $newOrder->push($insuranceType);
        }

        if ($newPosition >= $filteredInsuranceTypes->count()) {
            $newOrder->push($movedInsuranceType);
        }

        foreach ($newOrder as $index => $insuranceType) {
            InsuranceType::where('id', $insuranceType->id)->update(['order_column' => $index]);
        }

        $this->loadSubtypes();
    }

    public function save()
    {
        $validated = $this->validate();

        $validated['slug'] = Str::slug($validated['name']);

        $insuranceSubtype = InsuranceSubtype::updateOrCreate(
            ['id' => $this->insuranceSubtypeId],
            $validated
        );
        $syncData = collect($this->assignedInsuranceTypes)->mapWithKeys(function($item, $index) {
            return [$item['id'] => ['order_id' => $index]];
        })->toArray();
        $insuranceSubtype->insuranceTypes()->sync($syncData);
        $this->dispatch('refreshInsuranceSubtypes');

        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.insurance-subtypes.insurance-subtypes-create-edit');
    }
}
