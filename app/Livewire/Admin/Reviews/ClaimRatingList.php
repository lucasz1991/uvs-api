<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ClaimRating;
use App\Models\Mail;
use Illuminate\Support\Facades\Log;

class ClaimRatingList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public $selectedRatings = [];
    public $selectAll = false;
    public $hasRatings = false;

    public $hasActiveRating = false;


    public $showMailModal = false;
    public $mailRatingId = null;
    public $mailSubject = '';
    public $mailHeader = '';
    public $mailBody = '';
    public $mailLink = '';

    public function updatingSearch()
    {   
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;

        if ($this->selectAll) {
            $this->selectedRatings = ClaimRating::pluck('id')->toArray();
        } else {
            $this->selectedRatings = [];
        }
    }

    public function toggleRatingSelection($ratingId)
    {
        if (in_array($ratingId, $this->selectedRatings)) {
            $this->selectedRatings = array_filter($this->selectedRatings, fn($id) => $id != $ratingId);
        } else {
            $this->selectedRatings[] = $ratingId;
        }
    }

    public function openMailModal($ratingId = null)
    {
        if ($ratingId) {
            $this->mailRatingId = $ratingId;
        } elseif (count($this->selectedRatings) === 0) {
            $this->dispatch('showAlert', 'Bitte wähle mindestens eine Bewertung aus.', 'info');
            return;
        }

        $this->showMailModal = true;
    }

    public function resetMailModal()
    {
        $this->showMailModal = false;
        $this->mailRatingId = null;
        $this->mailSubject = '';
        $this->mailHeader = '';
        $this->mailBody = '';
        $this->mailLink = '';
    }

    public function sendMail()
    {
        $this->validate([
            'mailSubject' => 'required|string|max:255',
            'mailHeader' => 'required|string|max:255',
            'mailBody' => 'required|string',
        ]);

        $content = [
            'subject' => $this->mailSubject,
            'header' => $this->mailHeader,
            'body' => $this->mailBody,
            'link' => $this->mailLink,
        ];

        $recipients = [];

        if ($this->mailRatingId) {
            $rating = ClaimRating::find($this->mailRatingId);
            if ($rating && $rating->user) {
                $recipients[] = [
                    'user_id' => $rating->user->id,
                    'email' => $rating->user->email,
                    'status' => false,
                ];
            }
        } else {
            foreach ($this->selectedRatings as $id) {
                $rating = ClaimRating::find($id);
                if ($rating && $rating->user) {
                    $recipients[] = [
                        'user_id' => $rating->user->id,
                        'email' => $rating->user->email,
                        'status' => false,
                    ];
                }
            }
        }

        if (count($recipients) > 0) {
            Mail::create([
                'status' => false,
                'content' => $content,
                'recipients' => $recipients,
            ]);
            $this->dispatch('showAlert', 'E-Mail wurde vorbereitet.', 'success');
        }

        $this->resetMailModal();
    }

    public function reanalyse( $ratingId ){
        $claimRating = ClaimRating::find($ratingId);
        $claimRating->reanalyse();
    }

    public function render()
    {
        $ratings = ClaimRating::with('insurance', 'user')
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('answers->Service-Kommentar', 'like', '%' . $this->search . '%')
                ->orWhere('attachments->scorings->ai_overall_comment', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $this->hasRatings = $ratings->count() > 0;
        $this->hasActiveRating = ClaimRating::where('status', 'rating')->exists();


        return view('livewire.admin.reviews.claim-rating-list', [
            'ratings' => $ratings,
            'hasActiveRating' => $this->hasActiveRating,
        ])->layout('layouts.master');
    }
}
