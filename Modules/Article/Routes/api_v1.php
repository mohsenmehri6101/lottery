<?php

use Illuminate\Support\Facades\Route;
use Modules\Article\Http\Controllers\AttributeController;
use Modules\Article\Http\Controllers\CategoryController;
use Modules\Article\Http\Controllers\CommentController;
use Modules\Article\Http\Controllers\CommonComplaintController;
use Modules\Article\Http\Controllers\ComplaintController;
use Modules\Article\Http\Controllers\QrCodeController;
use Modules\Article\Http\Controllers\ReserveController;
use Modules\Article\Http\Controllers\ReserveTemplateController;
use Modules\Article\Http\Controllers\KeywordController;
use Modules\Article\Http\Controllers\ArticleController;
use Modules\Article\Http\Controllers\ScoreController;
use Modules\Article\Http\Controllers\SeederController;
use Modules\Article\Http\Controllers\SportController;
use Modules\Article\Http\Controllers\TagController;

Route::get('/get-initialize-requests-selectors', [ArticleController::class, 'getInitializeRequestsSelectors'])->name('index');

# articles
Route::prefix('articles')->name('articles_')->group(function () {
    Route::get('/my-articles', [ArticleController::class, 'myArticles'])->name('my-articles');
    Route::get('article-status', [ArticleController::class, 'articlesStatus'])->name('article_status');
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
    Route::post('/toggle-article-activated/{id}', [ArticleController::class, 'toggleArticleActivated'])->name('toggle_article_activated');
    Route::post('/article-free', [ArticleController::class, 'storeFree'])->name('article_free');
    Route::post('/', [ArticleController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/{id}', [ArticleController::class, 'update'])->middleware('auth:api')->name('post_update');
    Route::post('/like', [ArticleController::class, 'like'])->middleware('auth:api')->name('like');
    Route::put('/{id}', [ArticleController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::delete('/delete-image/{id}', [ArticleController::class, 'deleteImage'])->middleware('auth:api')->name('delete_image');
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
    Route::get('/my-article-reserves', [ReserveController::class, 'myArticleReserve'])->name('my-article-reserves');
    Route::get('/{id}', [ReserveController::class, 'show'])/*->middleware('auth:api')*/->name('show');
    Route::post('/', [ReserveController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/store-and-print-factor-and-create-link-payment', [ReserveController::class, 'storeAndPrintFactorAndCreateLinkPayment'])->middleware('auth:api')->name('store_and_print_factor_and_create_link_payment');
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
    Route::post('/sync-sport-to-article', [SportController::class, 'syncSportToArticle'])->middleware('auth:api')->name('sync_sport_to_article');
    Route::post('/delete-sport-to-article', [SportController::class, 'deleteSportToArticle'])->middleware('auth:api')->name('delete_sport_to_article');
});

# attributes
Route::prefix('attributes')->name('attributes_')->group(function () {
    Route::get('/', [AttributeController::class, 'index'])->name('index');
    Route::get('/{id}', [AttributeController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [AttributeController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [AttributeController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [AttributeController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-attribute-to-article', [AttributeController::class, 'syncAttributeToArticle'])->middleware('auth:api')->name('sync_attribute_to_article');
    Route::post('/delete-attribute-to-article', [AttributeController::class, 'deleteAttributeToArticle'])->middleware('auth:api')->name('delete_attribute_to_article');
});

# qr_codes
Route::prefix('qr-codes')->name('qr_codes_')->group(function () {
    Route::get('/', [QrCodeController::class, 'index'])->name('index');
    Route::get('/{id}', [QrCodeController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [QrCodeController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [QrCodeController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [QrCodeController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});


# categories
Route::prefix('categories')->name('categories_')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{id}', [CategoryController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [CategoryController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [CategoryController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-category-to-article', [CategoryController::class, 'syncCategoryToArticle'])->middleware('auth:api')->name('sync_category_to_article');
    Route::post('/delete-category-to-article', [CategoryController::class, 'deleteCategoryToArticle'])->middleware('auth:api')->name('delete_category_to_article');
});

# tags
Route::prefix('tags')->name('tags_')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('index');
    Route::get('/{id}', [TagController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [TagController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [TagController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [TagController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-tag-to-article', [TagController::class, 'syncTagToArticle'])->middleware('auth:api')->name('sync_tag_to_article');
    Route::post('/delete-tag-to-article', [TagController::class, 'deleteTagToArticle'])->middleware('auth:api')->name('delete_tag_to_article');
});

# keywords
Route::prefix('keywords')->name('keywords_')->group(function () {
    Route::get('/', [KeywordController::class, 'index'])->name('index');
    Route::get('/{id}', [KeywordController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [KeywordController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [KeywordController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [KeywordController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::post('/sync-keyword-to-article', [KeywordController::class, 'syncKeywordToArticle'])->middleware('auth:api')->name('sync_keyword_to_article');
    Route::post('/delete-keyword-to-article', [KeywordController::class, 'deleteKeywordToArticle'])->middleware('auth:api')->name('delete_keyword_to_article');
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
