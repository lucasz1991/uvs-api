<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use App\Models\ClaimRating;
use App\Jobs\ClaimRatingAIEval;

class ShowClaimRating extends Component
{
    public $ratingId;
    public $rating;

    public function mount($ratingId)
    {
        $this->ratingId = $ratingId;
        $this->rating = ClaimRating::with('user', 'insurance', 'insuranceSubtype')->findOrFail($ratingId);
    }

    public function reanalyse( $ratingId ){
        $this->rating = ClaimRating::find($ratingId);
        $this->rating->reanalyse();

    }

    public function render()
    {
        return view('livewire.admin.reviews.show-claim-rating')
            ->layout('layouts.master');
    }
}
