<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Coupon;
use App\Models\Bonus;
use Illuminate\Support\Facades\Validator;

class CouponBonusSettings extends Component
{
    public $coupons;
    public $bonuses;
    public $couponSelected;
    public $bonusSelected;
    public $bonusModalOpen;

    // Formulareingaben für Coupons und Boni
    public $couponCode;
    public $couponDescription;
    public $couponDiscountType;
    public $couponDiscountValue;
    public $couponMinOrderValue;
    public $couponMaxDiscountValue;
    public $couponStartDate;
    public $couponEndDate;
    public $couponUsageLimit;
    public $couponUserSpecific;
    public $couponAppliesTo;
    public $couponStatus;

        // Bonus-Formularvariablen
        public $bonusName;
        public $bonusDescription;
        public $bonusType;
        public $bonusValue;
        public $bonusValidityPeriod;
        public $bonusUserId;
        public $bonusStatus;
        public $bonusIsRedeemable;
        public $bonusValidFrom;
        public $bonusValidUntil;
        public $bonusBookingStartFrom;
        public $bonusBookingStartUntil;
        public $bonusPeriodWeekly = false;
        public $bonusPeriodBiweekly = false;
        public $bonusPeriodThreeWeeks = false;
        public $bonusCustomerRequirement;

    public function mount()
    {
        // Coupons und Boni beim Laden der Seite abrufen
        $this->coupons = Coupon::all();
        $this->bonuses = Bonus::all();
        $this->couponSelected = false;
        $this->bonusSelected = false;
    }

    // Speichern von Coupon-Daten (Erstellen oder Bearbeiten)
    public function saveCoupon()
    {
        $validatedData = Validator::make([
            'couponCode' => 'required|string|unique:coupons,code,' . ($this->couponSelected ? $this->couponSelected->id : ''),
            'couponDescription' => 'required|string',
            'couponDiscountType' => 'required|integer',
            'couponDiscountValue' => 'required|numeric',
            'couponStartDate' => 'required|date',
            'couponEndDate' => 'required|date',
        ])->validate();

        if ($this->couponSelected) {
            // Bearbeiten eines Coupons
            $this->couponSelected->update([
                'code' => $this->couponCode,
                'description' => $this->couponDescription,
                'discount_type' => $this->couponDiscountType,
                'discount_value' => $this->couponDiscountValue,
                'min_order_value' => $this->couponMinOrderValue,
                'max_discount_value' => $this->couponMaxDiscountValue,
                'start_date' => $this->couponStartDate,
                'end_date' => $this->couponEndDate,
                'usage_limit' => $this->couponUsageLimit,
                'user_specific' => $this->couponUserSpecific,
                'applies_to' => $this->couponAppliesTo,
                'status' => $this->couponStatus,
            ]);
        } else {
            // Erstellen eines neuen Coupons
            Coupon::create([
                'code' => $this->couponCode,
                'description' => $this->couponDescription,
                'discount_type' => $this->couponDiscountType,
                'discount_value' => $this->couponDiscountValue,
                'min_order_value' => $this->couponMinOrderValue,
                'max_discount_value' => $this->couponMaxDiscountValue,
                'start_date' => $this->couponStartDate,
                'end_date' => $this->couponEndDate,
                'usage_limit' => $this->couponUsageLimit,
                'user_specific' => $this->couponUserSpecific,
                'applies_to' => $this->couponAppliesTo,
                'status' => $this->couponStatus,
            ]);
        }

        // Nach dem Speichern alle Coupons neu laden
        $this->coupons = Coupon::all();
        $this->resetCouponForm();
    }

    // Speichern von Bonus-Daten (Erstellen oder Bearbeiten)
    public function saveBonus()
    {
        $validatedData = $this->validate([
            'bonusName' => 'required|string|unique:bonuses,name,' . ($this->bonusSelected ? $this->bonusSelected->id : ''),
            'bonusDescription' => 'required|string',
            'bonusType' => 'required|string|in:percentage,amount',
            'bonusValue' => 'required|numeric|min:0',
            'bonusValidityPeriod' => 'nullable|integer|min:1',
            'bonusUserId' => 'nullable|integer|exists:users,id',
            'bonusStatus' => 'required|boolean',
    
            // Neue Bonus-Kriterien
            'bonusValidFrom' => 'nullable|date',
            'bonusValidUntil' => 'nullable|date|after_or_equal:bonusValidFrom',
            'bonusBookingStartFrom' => 'nullable|date',
            'bonusBookingStartUntil' => 'nullable|date|after_or_equal:bonusBookingStartFrom',
            'bonusCustomerRequirement' => 'required|in:new,existing,all',
    
            // Perioden (zum JSON-Array umgewandelt)
            'bonusPeriodWeekly' => 'boolean',
            'bonusPeriodBiweekly' => 'boolean',
            'bonusPeriodThreeWeeks' => 'boolean',
        ]);
    
        // Perioden als JSON speichern
        $periods = [];
        if ($this->bonusPeriodWeekly) $periods[] = 7;
        if ($this->bonusPeriodBiweekly) $periods[] = 14;
        if ($this->bonusPeriodThreeWeeks) $periods[] = 21;
    
        if ($this->bonusSelected) {
            // Bestehenden Bonus aktualisieren
            $this->bonusSelected->update([
                'name' => $this->bonusName,
                'description' => $this->bonusDescription,
                'type' => $this->bonusType,
                'value' => $this->bonusValue,
                'valid_from' => $this->bonusValidFrom,
                'valid_until' => $this->bonusValidUntil,
                'booking_start_from' => $this->bonusBookingStartFrom,
                'booking_start_until' => $this->bonusBookingStartUntil,
                'booking_start_until' => $this->bonusBookingStartUntil,
                'booking_end_from' => '0000 -00 -00 00: 00: 00',
                'booking_end_until' => '0000 -00 -00 00: 00: 00',
                'periods' => json_encode($periods), 
                'customer_requirement' => $this->bonusCustomerRequirement,
                'validity_period' => null,
                'user_id' => $this->bonusUserId ?: null,
                'status' => $this->bonusStatus,
                'is_redeemable' => true,
            ]);
        } else {
            // Neuen Bonus erstellen
            Bonus::create([
                'name' => $this->bonusName,
                'description' => $this->bonusDescription,
                'type' => $this->bonusType,
                'value' => $this->bonusValue,
                'valid_from' => $this->bonusValidFrom,
                'valid_until' => $this->bonusValidUntil,
                'booking_start_from' => $this->bonusBookingStartFrom,
                'booking_start_until' => $this->bonusBookingStartUntil,
                'booking_end_from' => '0000 -00 -00 00: 00: 00',
                'booking_end_until' => '0000 -00 -00 00: 00: 00',
                'periods' => json_encode($periods), 
                'customer_requirement' => $this->bonusCustomerRequirement,
                'validity_period' => null,
                'user_id' => $this->bonusUserId ?: null,
                'status' => $this->bonusStatus,
                'is_redeemable' => true,
            ]);
        }
    
        // Nach dem Speichern alle Boni neu laden
        $this->bonuses = Bonus::all();
        $this->resetBonusForm();
    
        // Erfolgsmeldung
        session()->flash('success', 'Bonus erfolgreich gespeichert.');
    }

    // Löschen eines Coupons
    public function deleteCoupon($couponId)
    {
        Coupon::find($couponId)->delete();
        $this->coupons = Coupon::all();
    }

    // Löschen eines Bonus
    public function deleteBonus($bonusId)
    {
        Bonus::find($bonusId)->delete();
        $this->bonuses = Bonus::all();
    }

    // Formular für Coupon zurücksetzen
    private function resetCouponForm()
    {
        $this->couponSelected = false;
        $this->couponCode = '';
        $this->couponDescription = '';
        $this->couponDiscountType = '';
        $this->couponDiscountValue = '';
        $this->couponMinOrderValue = '';
        $this->couponMaxDiscountValue = '';
        $this->couponStartDate = '';
        $this->couponEndDate = '';
        $this->couponUsageLimit = '';
        $this->couponUserSpecific = false;
        $this->couponAppliesTo = '';
        $this->couponStatus = 1;
    }

    // Formular für Bonus zurücksetzen
    private function resetBonusForm()
    {
        $this->bonusSelected = false;
        $this->bonusName = '';
        $this->bonusDescription = '';
        $this->bonusType = '';
        $this->bonusValue = '';
        $this->bonusValidityPeriod = '';
        $this->bonusUserId = '';
        $this->bonusStatus = 1;
        $this->bonusIsRedeemable = true;

        // Neue Bonus-Kriterien zurücksetzen
        $this->bonusValidFrom = '';
        $this->bonusValidUntil = '';
        $this->bonusBookingStartFrom = '';
        $this->bonusBookingStartUntil = '';
        $this->bonusPeriodWeekly = false;
        $this->bonusPeriodBiweekly = false;
        $this->bonusPeriodThreeWeeks = false;
        $this->bonusCustomerRequirement = '';
    }

    public function render()
    {
        return view('livewire.admin.coupon-bonus-settings')->layout('layouts.master');
    }
}
