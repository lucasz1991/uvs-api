<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ParticipantApiController;
use App\Http\Controllers\Api\PersonApiController;
use App\Http\Controllers\Api\TutorApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\AssetsApiController;

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

Route::get('/course/coursebyklassenid', [CourseApiController::class, 'getCourseByKlassenId'])->name('course.get');

Route::get('/course/day-attendance', [CourseApiController::class, 'getCourseDayAttendanceData'])->name('course.day-attendance.get');

Route::post('/course/courseday/syncattendancedata', [CourseApiController::class, 'syncCourseDayAttendanceData'])->name('course.day-attendance.sync');

Route::post('/course/courseresults/syncdata',[CourseApiController::class, 'syncCourseResultsData'])->name('course.results.sync');

Route::get('/assets/institutions', [AssetsApiController::class, 'getInstitutions'])->name('assets.institutions.get');

Route::post('/sql', [SqlApiController::class, 'run'])->name('sql.run');

