<?php

namespace App\Livewire\Admin\RatingStructure\Insurance;

use Livewire\Component;
use App\Models\Insurance;
use App\Models\InsuranceType;

class CreateEdit extends Component
{
    public $insurance;
    public $insuranceId;
    public $name;
    public $slug;
    public $description;
    public $initials;
    public $style = [
        'font_color' => null,
        'border_color' => null,
        'bg_color' => null
    ];
    public $is_active = true;
    public $assignedInsuranceTypes = [];
    public $availableInsuranceTypes = [];
    public $insuranceTypeToAdd = null;
    public $showModal = false;

    protected $listeners = ['open-insurance-form' => 'open'];

    public function open($insuranceId = null)
    {
        $this->reset();
        $this->showModal = true;

        if ($insuranceId) {
            $this->insuranceId = $insuranceId;
            $this->insurance = Insurance::with('insuranceTypes')->findOrFail($insuranceId);
            $this->availableInsuranceTypes = InsuranceType::whereDoesntHave('insurances', function ($query) use ($insuranceId) {
                if ($insuranceId) {
                    $query->where('insurance_id', $insuranceId);
                }
            })->orderBy('name')->get();
            $this->assignedInsuranceTypes = $this->insurance->insuranceTypes
                ->map(fn($i) => ['id' => $i->id, 'name' => $i->name])
                ->values()
                ->toArray();
            $this->name = $this->insurance->name;
            $this->slug = $this->insurance->slug;
            $this->description = $this->insurance->description;
            $this->initials = $this->insurance->initials;
            $this->style = $this->insurance->style;
            $this->is_active = $this->insurance->is_active;
        }
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

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initials' => 'nullable|string|max:10',
            'style' => 'nullable|array',
            'style.*' => 'string|max:255',
            'is_active' => 'boolean',
            'assignedInsuranceTypes' => 'array',
        ]);

        $insurance = Insurance::updateOrCreate(
            ['id' => $this->insuranceId],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'initials' => $this->initials,
                'style' => $this->style,
                'is_active' => $this->is_active,
            ]
        );

        $syncData = collect($this->assignedInsuranceTypes)->mapWithKeys(function($item, $index) {
            return [$item['id'] => ['order_column' => $index]];
        })->toArray();
        $insurance->insuranceTypes()->sync($syncData);


        $this->showModal = false;
        $this->dispatch('refreshInsurances');
    } 
    
    public function removeInsuranceType($id)
    {
        $this->assignedInsuranceTypes = collect($this->assignedInsuranceTypes)
            ->reject(fn($i) => $i['id'] == $id)
            ->values()
            ->toArray();
        $this->availableInsuranceTypes[] = InsuranceType::find($id);
    }


    public function render()
    {
        return view('livewire.admin.rating-structure.insurance.create-edit', [
            'allTypes' => InsuranceType::all(),
        ]);
    }
}