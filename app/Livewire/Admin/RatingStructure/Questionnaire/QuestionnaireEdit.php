<?php

namespace App\Livewire\Admin\RatingStructure\Questionnaire;

use Livewire\Component;
use App\Models\InsuranceType;
use App\Models\InsuranceSubtype;
use App\Models\RatingQuestion;

class QuestionnaireEdit extends Component
{
    public $insuranceSubTypeId;
    public $insuranceSubType;
    public $availableQuestions = [];
    public $assignedQuestions = [];
    public $questionToAdd = null;
    public $showModal = false;

    protected $listeners = ['open-formbuilder' => 'open'];

    public function open($typeId)
    {
        $this->reset(['insuranceSubTypeId', 'availableQuestions', 'assignedQuestions', 'questionToAdd']);
        $this->insuranceSubTypeId = $typeId;
        $this->insuranceSubType = InsuranceSubtype::findOrFail($typeId);

        $this->loadData();
        $this->showModal = true;
    }

    public function loadData()
    {
        $this->insuranceSubType->load(['ratingQuestions' => function ($q) {
            $q->orderBy('insurance_subtype_rating_question.order_column');
        }]);

        $assignedIds = $this->insuranceSubType->ratingQuestions->pluck('id')->toArray();
        $this->assignedQuestions = $this->insuranceSubType->ratingQuestions
            ->map(fn($i) => ['id' => $i->id, 'title' => $i->title, 'type' => $i->type, 'order_column' => $i->pivot->order_column])
            ->values()
            ->toArray();

        $this->availableQuestions = RatingQuestion::whereNotIn('id', $assignedIds)->get();
    }

    public function addQuestion()
    {
        if (!$this->questionToAdd) return;

        $question = RatingQuestion::find($this->questionToAdd);

        if (!$question) return;

        $this->assignedQuestions[] = [
            'id' => $question->id,
            'title' => $question->title,
            'type' => $question->type,
            'order_column' => count($this->assignedQuestions),
        ];

        $this->questionToAdd = null;

        // Optional: Refresh availableQuestions
        $this->availableQuestions = RatingQuestion::whereNotIn('id', collect($this->assignedQuestions)->pluck('id'))->get();

    }

    public function removeQuestion($id)
    {
        $this->assignedQuestions = collect($this->assignedQuestions)->reject(fn ($q) => $q['id'] == $id)->values()->toArray();
    }

    public function reorder($items)
    {
        $this->assignedQuestions = collect($items)->map(function ($item, $index) {
            return array_merge($item, ['order_column' => $index]);
        })->toArray();
    }

    public function save()
    {
        $syncData = [];

        foreach ($this->assignedQuestions as $index => $q) {
            $syncData[$q['id']] = [
                'order_column' => $index,
            ];
        }

        $this->insuranceSubType->ratingQuestions()->sync($syncData);

        $this->dispatch('refreshQuestionnaires');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.rating-structure.questionnaire.questionnaire-edit');
    }
}
