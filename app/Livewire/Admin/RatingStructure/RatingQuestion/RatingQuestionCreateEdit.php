<?php

namespace App\Livewire\Admin\RatingStructure\RatingQuestion;

use Livewire\Component;
use App\Models\RatingQuestion;

class RatingQuestionCreateEdit extends Component
{
    public $questionId;
    public $title;
    public $question_text;
    public $type = 'text'; // z. B. text, select, number
    public $is_required = false;
    public $help_text;
    public $frontend_title;
    public $frontend_description;
    public $input_constraints;
    public $tags;
    public $is_active = true;
    public $read_only = false;

    public $showModal = false;

    protected $listeners = ['open-rating-question-form' => 'open'];

    public function open($id = null)
    {
        $this->reset([
            'title', 'questionId', 'question_text', 'type', 'is_required',
            'help_text', 'frontend_title', 'frontend_description',
            'input_constraints', 'tags', 'is_active', 'read_only',
        ]);

        $this->showModal = true;

        if ($id) {
            $q = RatingQuestion::findOrFail($id);
            $this->questionId = $q->id;
            $this->title = $q->title;
            $this->question_text = $q->question_text;
            $this->type = $q->type;
            $this->is_required = $q->is_required;
            $this->help_text = $q->help_text;
            $this->frontend_title = $q->frontend_title;
            $this->frontend_description = $q->frontend_description;
            $this->input_constraints = json_encode($q->input_constraints ?? []);
            $this->tags = is_array($q->tags) ? implode(', ', $q->tags) : $q->tags;
            $this->is_active = $q->is_active;
            $this->read_only = $q->read_only;
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => 'nullable|string|max:255',
            'question_text' => 'required|string|max:500',
            'type' => 'required|string|max:50',
            'is_required' => 'boolean',
            'help_text' => 'nullable|string|max:1000',
            'frontend_title' => 'nullable|string|max:255',
            'frontend_description' => 'nullable|string|max:1000',
            'input_constraints' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_active' => 'boolean',
            'read_only' => 'boolean',
        ]);

        $validated['input_constraints'] = json_decode($validated['input_constraints'], true);
        $validated['tags'] = array_map('trim', explode(',', $validated['tags'] ?? ''));

        RatingQuestion::updateOrCreate(
            ['id' => $this->questionId],
            $validated
        );

        $this->dispatch('refreshRatingQuestions');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.rating-question.rating-question-create-edit');
    }
}
