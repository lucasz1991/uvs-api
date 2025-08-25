<?php

namespace App\Livewire\Admin\Courses;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Course;

class CourseList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'title';
    public $sortDir = 'asc';
    public $perPage = 10;
    public $coursesTotal;


    protected $listeners = [
        'openCourseSettings' => 'refreshList',
        'refreshCourses'     => 'refreshList',
        'table-sort'         => 'tableSort',
    ];
    protected $queryString = ['search', 'sortBy', 'sortDir', 'perPage'];

    // Wenn du das Pagination-Layout/Themes nutzt:
    // protected string $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->coursesTotal = Course::count();
    }

    public function updatedSearch()
    {
        $this->resetPage(); // wichtig bei Filteränderung
    }

    public function tableSort($key, $dir)
    {
        $this->sortBy = $key;
        $this->sortDir = $dir;
        $this->resetPage(); // falls Paginator vorhanden
    }

    public function updatingPerPage()
    {
        $this->resetPage(); // Reset der Seite bei Änderung der Anzahl pro Seite
    }

    public function refreshList()
    {
        // Events wie openCourseSettings/refreshCourses triggern nur ein Refresh
        $this->resetPage();
    }

protected function query()
{
    $q = Course::query()
        ->leftJoin('users as tutors', 'tutors.id', '=', 'courses.tutor_id')
        ->select('courses.*')
        ->with('tutor')
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('courses.title', 'like', '%' . $this->search . '%')
                  ->orWhere('tutors.name', 'like', '%' . $this->search . '%');
            });
        });

    $allowed = ['title', 'created_at', 'updated_at', 'tutor_name'];
    $sortBy  = in_array($this->sortBy, $allowed, true) ? $this->sortBy : 'title';

    if ($sortBy === 'tutor_name') {
        $q->orderBy('tutors.name', $this->sortDir);
    } else {
        $q->orderBy('courses.' . $sortBy, $this->sortDir);
    }

    return $q;
}


    public function render()
    {
        $courses = $this->query()->paginate($this->perPage);

        return view('livewire.admin.courses.course-list', compact('courses'))
            ->layout('layouts.master');
    }
}
