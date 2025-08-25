<?php

namespace App\Livewire\Admin\RatingStructure;

use Livewire\Component;
use App\Models\Insurance;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class InsuranceList extends Component
{
    use WithPagination, WithoutUrlPagination; 
    
    public $insurancesAll = [];

    protected $listeners = [
        'refreshInsurances' => 'loadInsurances',
        'orderInsurance' => 'handleOrderInsurance'
    ];

    public function mount()
    {
        $this->loadInsurances();
    }

    public function loadInsurances()
    {
        $this->insurancesAll = Insurance::orderBy('order_column')->get();
    }

    public function updatingPage()
    {
        $this->resetPage();
    }
    

    public function handleOrderInsurance($item, $position)
    {
        if (!isset($item, $position)) {
            return;
        }

        $movedInsurance = Insurance::find($item['id']);
        if (!$movedInsurance) {
            return;
        }

        $newPosition = (int) $position;

        $insurances = Insurance::orderBy('order_column')->get();
        $filteredInsurances = $insurances->reject(fn ($i) => $i->id == $movedInsurance->id)->values();

        $newOrder = collect();
        foreach ($filteredInsurances as $index => $insurance) {
            if ($index == $newPosition) {
                $newOrder->push($movedInsurance);
            }
            $newOrder->push($insurance);
        }

        if ($newPosition >= $filteredInsurances->count()) {
            $newOrder->push($movedInsurance);
        }

        foreach ($newOrder as $index => $insurance) {
            Insurance::where('id', $insurance->id)->update(['order_column' => $index]);
        }

        $this->resetPage();
    }

    public function deleteInsurance($id)
    {
        Insurance::findOrFail($id)->delete();
        $this->loadInsurances();
        $this->resetPage();
    }



    public function toggleActive($id)
    {
        $insurance = Insurance::findOrFail($id);
        $insurance->update(['is_active' => !$insurance->is_active]);
        $this->loadInsurances();
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.insurance-list', [
            'insurances' => Insurance::orderBy('order_column')->paginate(10),
        ]);
    }
}
