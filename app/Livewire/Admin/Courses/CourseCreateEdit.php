<?php

namespace App\Livewire\Admin\Courses;

use App\Models\Course;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;

class CourseCreateEdit extends Component
{
    public $showModal = false;
    public $courseId;
    public $course;

    public $title;
    public $description;
    public $start_time;
    public $end_time;
    public $tutor_id;
    public $participants = [];

    public $tutors = [];
    public $possibleParticipants = [];

    // HIER sammeln wir die berechneten Kursdaten (Y-m-d Strings)
    public $courseDates = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_time' => 'nullable|date',
        'end_time' => 'nullable|date|after_or_equal:start_time',
        'tutor_id' => 'required|exists:users,id',
        'participants' => 'nullable|array',
        'participants.*' => 'exists:users,id',
    ];

    protected $listeners = ['open-course-create-edit' => 'loadCourse'];

    public function loadCourse($courseId = null)
    {
        $this->reset(['title', 'description', 'start_time', 'end_time', 'tutor_id', 'courseId', 'participants', 'courseDates', 'course']);

        if ($courseId) {
            $this->course = Course::findOrFail($courseId);

            $this->courseId    = $this->course->id;
            $this->title       = $this->course->title;
            $this->description = $this->course->description;
            $this->start_time  = $this->course->start_time?->format('Y-m-d');
            $this->end_time    = $this->course->end_time?->format('Y-m-d');
            $this->tutor_id    = $this->course->tutor_id;
            $this->participants = $this->course->participants()->pluck('users.id')->toArray();
                $this->courseDates = $this->course->dates()
                    ->orderBy('date')
                    ->get(['date'])
                    ->map(fn($d) => \Illuminate\Support\Carbon::parse($d->date)->format('Y-m-d'))
                    ->toArray();
        } else {
            // Neuer Kurs: wenn Start/Ende gesetzt sind, direkt berechnen
            $this->generateWeekdays();
        }

        $this->showModal = true;
    }

    /** Wird aufgerufen, wenn Start/Ende in der UI geändert werden */
    public function updatedStartTime()
    {
        $this->generateWeekdays();
    }

    public function updatedEndTime()
    {
        $this->generateWeekdays();
    }

    /**
     * Berechnet alle Montags–Freitags-Daten zwischen start_time und end_time (inkl.)
     * und schreibt sie als 'Y-m-d' in $this->courseDates
     */
    protected function generateWeekdays()
    {
        $this->courseDates = [];

        if (!$this->start_time || !$this->end_time) {
            return;
        }

        $start = Carbon::parse($this->start_time)->startOfDay();
        $end   = Carbon::parse($this->end_time)->endOfDay();

        if ($end->lt($start)) {
            return; // Validation kümmert sich, aber hier brechen wir sauber ab
        }

        $period = CarbonPeriod::create($start, $end);
        $dates = [];
        foreach ($period as $day) {
            // isWeekday() = Mo–Fr
            if ($day->isWeekday()) {
                $dates[] = $day->format('Y-m-d');
            }
        }

        $this->courseDates = $dates;
    }

    public function saveCourse()
    {
        $this->validate();

        $course = Course::updateOrCreate(
            ['id' => $this->courseId],
            [
                'title'       => $this->title,
                'description' => $this->description,
                'start_time'  => $this->start_time ? Carbon::parse($this->start_time) : null,
                'end_time'    => $this->end_time ? Carbon::parse($this->end_time) : null,
                'tutor_id'    => $this->tutor_id,
            ]
        );

        // Teilnehmer syncen
        $course->participants()->sync($this->participants ?? []);
        // ⬇ Nur wenn courseDates nicht leer ist, synchronisieren wir die Kurstage

        if (!empty($this->courseDates)) {
            $keep = $this->courseDates;

            // löschen, was nicht mehr drin ist
            $course->dates()->whereNotIn('date', $keep)->delete();

            // fehlende anlegen
            $existing = $course->dates()->pluck('date')->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))->toArray();
            $toCreate = array_values(array_diff($keep, $existing));
            foreach ($toCreate as $d) {
                $course->dates()->create(['date' => Carbon::parse($d)]);
            }
        }

        session()->flash('message', 'Kurs gespeichert.');
        $this->reset(['title', 'description', 'start_time', 'end_time', 'tutor_id', 'courseId', 'participants', 'courseDates', 'course']);
        $this->showModal = false;

        $this->dispatch('refreshCourses');
    }

    public function closeModal()
    {
        $this->reset(['title', 'description', 'start_time', 'end_time', 'tutor_id', 'courseId', 'participants', 'courseDates', 'course']);
        $this->showModal = false;
    }

    public function render()
    {
        $this->tutors = User::where('role', 'tutor')->get();
        $this->possibleParticipants = User::where('role', 'guest')->get();

        return view('livewire.admin.courses.course-create-edit', [
            'tutors' => $this->tutors,
            'possibleParticipants' => $this->possibleParticipants,
            'courseDates' => $this->courseDates,
            'course' => $this->course,

        ]);
    }
}
