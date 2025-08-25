<?php

namespace App\Livewire\Shelfrentalcomponents;

use Livewire\Component;
use App\Models\ShelfRental;
use App\Models\Product;
use App\Models\AdminTask;
use App\Jobs\TransferDiscountToCashRegisterJob;
use Illuminate\Validation\Rule;



class ShelfRentalDiscount extends Component
{
    public $shelfRental;
    public $discount;

    protected function rules()
    {
        return [
            'discount' => [
                'required',
                'in:0,25,50',
                Rule::notIn([$this->shelfRental->discount]) // Verhindert, dass der gleiche Wert gespeichert wird
            ]
        ];
    }

    protected function messages()
    {
        return [
            'discount.not_in' => 'Der neue Rabatt muss sich vom aktuellen Rabatt unterscheiden.'
        ];
    }

    public function mount(ShelfRental $shelfRental)
    {
        $this->shelfRental = $shelfRental;
        $this->discount = $shelfRental->discount; // Setzt den aktuellen Rabatt
    }

    public function applyDiscount()
    {
        $this->validate();

        try {
            // Rabatt für die Regalbuchung setzen
            $this->shelfRental->discount = $this->discount;
            $this->shelfRental->save();

            // Rabatt für die aktiven Produkte setzen
            foreach ($this->shelfRental->products->where('status', 2) as $product) {
                $product->discount = $this->discount;
                $product->discount_price = ($this->discount == 0) ? null : round($product->price * (1 - ($this->discount / 100)), 2);
                $product->save();
            }

            // Admin-Task erstellen
            AdminTask::create([
                'task_type' => 'Rabattierung',
                'description' => "Regalbuchung #{$this->shelfRental->id} um {$this->discount}% reduziert",
                'status' => 0, // 0 = offen
                'assigned_to' => null, // Noch kein Admin zugewiesen
                'shelf_rental_id' => $this->shelfRental->id,
            ]);

            // Rabatt an die Kasse übertragen
            TransferDiscountToCashRegisterJob::dispatch($this->shelfRental);

            // Livewire-Events auslösen
            $this->dispatch('discount-applied'); // Aktualisiert andere Livewire-Komponenten
            $this->dispatch('showAlert', 'Rabatt wurde erfolgreich angewendet.', 'success');
        } catch (\Exception $e) {
            Log::error('Fehler beim Anwenden des Rabatts:', ['message' => $e->getMessage()]);
            $this->dispatch('showAlert', 'Ein Fehler ist aufgetreten. Bitte versuche es erneut.', 'error');
        }
    }

    public function render()
    {
        return view('livewire.shelfrentalcomponents.shelf-rental-discount');
    }
}
