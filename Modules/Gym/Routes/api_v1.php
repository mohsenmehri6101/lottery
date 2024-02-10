<?php

use Illuminate\Support\Facades\Route;
use Modules\Gym\Http\Controllers\AttributeController;
use Modules\Gym\Http\Controllers\CategoryController;
use Modules\Gym\Http\Controllers\CommentController;
use Modules\Gym\Http\Controllers\CommonComplaintController;
use Modules\Gym\Http\Controllers\ComplaintController;
use Modules\Gym\Http\Controllers\ReserveController;
use Modules\Gym\Http\Controllers\ReserveTemplateController;
use Modules\Gym\Http\Controllers\KeywordController;
use Modules\Gym\Http\Controllers\GymController;
use Modules\Gym\Http\Controllers\ScoreController;
use Modules\Gym\Http\Controllers\SeederController;
use Modules\Gym\Http\Controllers\SportController;
use Modules\Gym\Http\Controllers\TagController;

Route::get('/get-initialize-requests-selectors', [GymController::class, 'getInitializeRequestsSelectors'])->name('index');

# gyms
Route::prefix('gyms')->name('gyms_')->group(function () {
    Route::get('/', [GymController::class, 'index'])->name('index');
    Route::get('/my-gyms', [GymController::class, 'myGyms'])->name('index');
    Route::get('gym-status', [GymController::class, 'gymsStatus'])->name('gym_status');
    Route::get('/{id}', [GymController::class, 'show'])->name('show');
    Route::post('/', [GymController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/{id}', [GymController::class, 'update'])->middleware('auth:api')->name('post_update');
    Route::post('/like', [GymController::class, 'like'])->middleware('auth:api')->name('like');
    Route::put('/{id}', [GymController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [GymController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::delete('/delete-image/{id}', [GymController::class, 'deleteImage'])->middleware('auth:api')->name('delete_image');
});

# reserve_templates
Route::prefix('reserve_templates')->name('reserve_templates_')->group(function () {
    Route::get('/', [ReserveTemplateController::class, 'index'])->name('index');
    Route::get('gender-acceptances', [ReserveTemplateController::class, 'gender_acceptances'])->name('gender_acceptances');
    Route::get('statuses', [ReserveTemplateController::class, 'statuses'])->name('statuses');
    Route::get('between-date', [ReserveTemplateController::class, 'betweenDate'])->name('between_date');
    Route::get('/{id}', [ReserveTemplateController::class, 'show'])->name('show');
    Route::post('/', [ReserveTemplateController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('multiple', [ReserveTemplateController::class, 'multipleStore'])->middleware('auth:api')->name('multiple.store');
    Route::put('multiple', [ReserveTemplateController::class, 'multipleUpdate'])->middleware('auth:api')->name('multiple.update');
    Route::put('/{id}', [ReserveTemplateController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ReserveTemplateController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# complaints
Route::prefix('complaints')->name('complaints_')->group(function () {
    Route::get('/', [ComplaintController::class, 'index'])->name('index');
    Route::get('statuses', [ComplaintController::class, 'statuses'])->name('statuses');
    Route::get('/{id}', [ComplaintController::class, 'show'])->name('show');
    Route::post('/', [ComplaintController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [ComplaintController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ComplaintController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# common_complaints
Route::prefix('common-complaints')->name('common_complaints_')->group(function () {
    Route::get('/', [CommonComplaintController::class, 'index'])->name('index');
    Route::get('/{id}', [CommonComplaintController::class, 'show'])->name('show');
    Route::post('/', [CommonComplaintController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [CommonComplaintController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [CommonComplaintController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# reserves
Route::prefix('reserves')->name('reserves_')->group(function () {
    Route::get('/', [ReserveController::class, 'index'])->name('index');
    Route::get('/between-date', [ReserveController::class, 'reserveBetweenDates'])->name('between_date');
    Route::get('/statuses', [ReserveController::class, 'statuses'])->name('statuses');
    Route::get('/my-reserves', [ReserveController::class, 'myReserve'])->name('my-reserves');
    Route::get('/{id}', [ReserveController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [ReserveController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/store-and-print-factor-and-create-link-payment', [ReserveController::class, 'storeAndPrintFactorAndCreateLinkPayment'])->middleware('auth:api')->name('store_and_do_stuff');
    Route::post('/blocks', [ReserveController::class, 'storeBlocks'])->middleware('auth:api')->name('store_blocks');
    Route::put('/{id}', [ReserveController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ReserveController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# sports
Route::prefix('sports')->name('sports_')->group(function () {
    Route::get('/', [SportController::class, 'index'])->name('index');
    Route::get('/{id}', [SportController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [SportController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [SportController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [SportController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-sport-to-gym', [SportController::class, 'syncSportToGym'])->middleware('auth:api')->name('sync_sport_to_gym');
    Route::post('/delete-sport-to-gym', [SportController::class, 'deleteSportToGym'])->middleware('auth:api')->name('delete_sport_to_gym');
});

# attributes
Route::prefix('attributes')->name('attributes_')->group(function () {
    Route::get('/', [AttributeController::class, 'index'])->name('index');
    Route::get('/{id}', [AttributeController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [AttributeController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [AttributeController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [AttributeController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-attribute-to-gym', [AttributeController::class, 'syncAttributeToGym'])->middleware('auth:api')->name('sync_attribute_to_gym');
    Route::post('/delete-attribute-to-gym', [AttributeController::class, 'deleteAttributeToGym'])->middleware('auth:api')->name('delete_attribute_to_gym');
});

# categories
Route::prefix('categories')->name('categories_')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{id}', [CategoryController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [CategoryController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [CategoryController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-category-to-gym', [CategoryController::class, 'syncCategoryToGym'])->middleware('auth:api')->name('sync_category_to_gym');
    Route::post('/delete-category-to-gym', [CategoryController::class, 'deleteCategoryToGym'])->middleware('auth:api')->name('delete_category_to_gym');
});

# tags
Route::prefix('tags')->name('tags_')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('index');
    Route::get('/{id}', [TagController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [TagController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [TagController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [TagController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-tag-to-gym', [TagController::class, 'syncTagToGym'])->middleware('auth:api')->name('sync_tag_to_gym');
    Route::post('/delete-tag-to-gym', [TagController::class, 'deleteTagToGym'])->middleware('auth:api')->name('delete_tag_to_gym');
});

# keywords
Route::prefix('keywords')->name('keywords_')->group(function () {
    Route::get('/', [KeywordController::class, 'index'])->name('index');
    Route::get('/{id}', [KeywordController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [KeywordController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [KeywordController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [KeywordController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-keyword-to-gym', [KeywordController::class, 'syncKeywordToGym'])->middleware('auth:api')->name('sync_keyword_to_gym');
    Route::post('/delete-keyword-to-gym', [KeywordController::class, 'deleteKeywordToGym'])->middleware('auth:api')->name('delete_keyword_to_gym');
});

# comments
Route::prefix('comments')->name('comments_')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('index');
    Route::get('/my-comments', [CommentController::class, 'myComments'])->middleware('auth:api')->name('index');
    Route::get('/{id}', [CommentController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [CommentController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/like', [CommentController::class, 'like'])->middleware('auth:api')->name('like');
    Route::post('/dislike', [CommentController::class, 'dislike'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [CommentController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [CommentController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# scores
Route::prefix('scores')->name('scores_')->group(function () {
    Route::get('/', [ScoreController::class, 'index'])->name('index');
    Route::get('/{id}', [ScoreController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [ScoreController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [ScoreController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ScoreController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# seeders
Route::prefix('seeders')->name('seeders_')->middleware('auth:api')->group(function () {
    Route::post('/migrate-refresh-and-seeder-fake-all-data', [SeederController::class, 'migrateRefreshAndSeederFakeAllData'])->name('migrate-refresh-and-seeder-fake-all-data');
    Route::post('/seeder-fake-all-data', [SeederController::class, 'seederFakeAllData'])->name('seeder-fake-all-data');
});
