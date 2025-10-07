<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParticipantApiController;
use App\Http\Controllers\Api\PersonApiController;
use App\Http\Controllers\Api\TutorApiController;
use App\Http\Controllers\Api\CourseApiController;

use App\Http\Controllers\Api\SqlApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/participants/store', [ParticipantApiController::class, 'store'])->name('participants.store');

Route::get('/participants', [ParticipantApiController::class, 'get'])->name('participants.get');

Route::get('/participants/{participant}/qualiprogram', [ParticipantApiController::class, 'getParticipantAndQualiprogram'])->name('participants.qualiprogram.get');

Route::get('/person/status', [PersonApiController::class, 'getStatus'])->name('person.status.get');

Route::get('/tutorprogram/person', [TutorApiController::class, 'getTutorProgramByPersonId'])->name('tutorprogram.person.get');

Route::get('/course-classes', [CourseApiController::class, 'getCourseClasses'])->name('course-classes.get');

Route::get('/course-classes/participants', [CourseApiController::class, 'getCourseClassesParticipants'])->name('course-classes.participants.get');

Route::get('/Course/CourseByKlassenId', [CourseApiController::class, 'getCourseByKlassenId'])->name('course.get');


Route::post('/sql', [SqlApiController::class, 'run'])->name('sql.run');

