<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EventRegistrationController;




Route::middleware('auth:sanctum')->group(function () {

    /**
     * @route GET /api/user
     * @description বর্তমানে লগইন করা ইউজারের (অ্যাডমিন) তথ্য প্রদান করে।
     */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    /**
     * @route GET /api/registrations
     * @description অ্যাডমিন প্যানেলে দেখানোর জন্য সব রেজিস্ট্রেশনের তালিকা প্রদান করে।
     */
    Route::get('/admin/groups', [GroupController::class, 'adminIndex']); // অ্যাডমিন টেবিলের জন্য
    Route::post('/admin/groups', [GroupController::class, 'store']);
    Route::put('/admin/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/admin/groups/{group}', [GroupController::class, 'destroy']);
    Route::get('/admin/group-stats', [GroupController::class, 'getGroupStats']);
    Route::get('/admin/stats', [EventRegistrationController::class, 'getStats']);

    // --- ইউজার প্রোফাইল আপডেট ---
    Route::put('/user/profile-information', [ProfileController::class, 'updateProfile']);
    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    Route::get('/registrations', [EventRegistrationController::class, 'index']);

    // (ভবিষ্যতের জন্য: যদি কোনো নির্দিষ্ট রেজিস্ট্রেশনের বিস্তারিত দেখতে চান)
    // Route::get('/registration/{id}', [EventRegistrationController::class, 'show']);
});

// public routes
Route::get('/groups', [GroupController::class, 'index']);

Route::post('/register-event', [EventRegistrationController::class, 'store']);

Route::get('/registration/download/{id}', [EventRegistrationController::class, 'downloadEntryCard'])
    ->name('registration.download');

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
