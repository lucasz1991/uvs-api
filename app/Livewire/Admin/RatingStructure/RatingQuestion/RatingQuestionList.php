<?php

namespace App\Livewire\Admin\RatingStructure\RatingQuestion;

use Livewire\Component;
use App\Models\RatingQuestion;
use Illuminate\Database\Eloquent\Collection;

class RatingQuestionList extends Component
{
    public $questions = [];

    protected $listeners = [
        'refreshRatingQuestions' => 'loadQuestions'
    ];

    public function mount()
    {
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
     $this->questions = RatingQuestion::orderBy('id')->get();    
    }

    public function toggleActive($id)
    {
        $question = RatingQuestion::findOrFail($id);
        $question->update(['is_active' => !$question->is_active]);
        $this->loadQuestions();
    }

    public function delete($id)
    {
        RatingQuestion::findOrFail($id)->delete();
        $this->loadQuestions();
    }



    public function render()
    {
        return view('livewire.admin.rating-structure.rating-question.rating-question-list');
    }
}
