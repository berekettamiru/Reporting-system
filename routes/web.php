<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Report;
use App\Models\ReportItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard',
    [ReportController::class,'dashboard']
)->name('dashboard');


/*
|--------------------------------------------------------------------------
| AUTH USERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Reports
    Route::get('/reports',[ReportController::class,'index']);
    Route::get('/reports/my',[ReportController::class,'myReports']);
    Route::get('/reports/create',[ReportController::class,'create']);
    Route::post('/reports',[ReportController::class,'store']);
    Route::get('/reports/{id}',[ReportController::class,'show']);

    Route::get('/reports/{id}/edit',[ReportController::class,'edit']);
    Route::patch('/reports/{id}',[ReportController::class,'update']);
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->middleware('auth');

    // Profile
    Route::get('/profile',[ProfileController::class,'edit'])
        ->name('profile.edit');

    Route::put('/profile',[ProfileController::class,'update'])
        ->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class,'destroy'])
        ->name('profile.destroy');

    // User announcements
    Route::get('/announcements/announcementread', function () {

        $announcements =
        Announcement::with(['user','reads'])
        ->latest()
        ->get();

        return view(
            'Announcements.announcementread',
            compact('announcements')
        );
    });

});

//feedback
Route::get('/feedbacks', [ReportController::class, 'feedbacks']);
/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])->group(function(){

    Route::get('/admin/dashboard', function () {

        return view('admin.dashboard',[
            'usersCount'=>User::count(),
            'reportsCount'=>Report::count(),
            'visitsCount'=>ReportItem::count(),
            'recentReports'=>Report::with('user')->latest()->take(5)->get(),
            'topUsers'=>User::withCount('reports')->orderBy('reports_count','desc')->take(5)->get(),
        ]);

    });

    // users
    Route::get('/admin/users',[UserController::class,'index']);
    Route::get('/admin/users/create',[UserController::class,'create']);
    Route::post('/admin/users',[UserController::class,'store']);
    Route::delete('/admin/users/{id}',[UserController::class,'destroy']);
    Route::get('/admin/users/{id}/edit',[UserController::class,'edit']);
    Route::patch('/admin/users/{id}',[UserController::class,'update']);

    // announcements
    Route::get('/admin/announcements',
        [AnnouncementController::class,'index']);

    Route::get('/admin/announcements/create',
        [AnnouncementController::class,'create']);

    Route::post('/admin/announcements',
        [AnnouncementController::class,'store']);

    Route::get('/admin/announcements/{id}/reads',
        [AnnouncementController::class,'reads']);
    
    // Feedback
    Route::post('/admin/reports/{id}/feedback', [ReportController::class, 'updateFeedback']);

});
Route::post('/announcements/{id}/seen', function ($id) {

   AnnouncementRead::firstOrCreate(
        [
            'announcement_id' => $id,
            'user_id' => auth()->id(),
        ],
        [
            'read_at' => now(),
        ]
    );

    return back()->with('success', 'Marked as seen');

});
require __DIR__.'/auth.php';