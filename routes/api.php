<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// create new user account
Route::post('/user/signup/', 'ApiController@createUserAccount');

// create new anonymous user account
Route::post('/user/anoymous-signup/', 'ApiController@createAnonymousUserAccount');

// log in user account
Route::post('/user/login/', 'ApiController@signInUserAccount');

// request user verification code
Route::post('/user/request-verification-code', 'ApiController@requestUserVerificationCode');

// verify user code
Route::post('/user/verify-code/', 'ApiController@verifyCode');

// request password reset
Route::post('/user/request-password-reset', 'ApiController@requestPasswordReset');

// get user details
Route::get('/user/{userId}/get-details', 'ApiController@getUserDetails')->middleware('checkAPIUserToken');

// reset user details
Route::post('/user/reset-details', 'ApiController@resetUserDetails')->middleware('checkAPIUserToken');

// delete user account
Route::get('/user/{userId}/delete', 'ApiController@deleteUserAccount');

// upload question file
Route::post('/user/upload-question-file', 'ApiController@uploadQuestionFile')->middleware('checkAPIUserToken');

// submit user question
Route::post('/user/submit-question', 'ApiController@submitUserQuestion')->middleware('checkAPIUserToken');

// reply user question
Route::post('/user/reply-question', 'ApiController@replyUserQuestion')->middleware('checkAPIUserToken');

// gets user questions
Route::get('/user/{userId}/get-questions', 'ApiController@getUserQuestions')->middleware('checkAPIUserToken');

// get user question details
Route::get('/user/{userId}/get-question-details/{questionId}', 'ApiController@getUserQuestionDetails')->middleware('checkAPIUserToken');

// get article categories
Route::get('/articles/get-categories', 'ApiController@getArticleCategories')->middleware('checkAPIUserToken');

// get article by category name
Route::get('/articles/get-articles-by-category/{articleCategoryName}', 'ApiController@getArticlesByCategory')->middleware('checkAPIUserToken');

// fetches an article by id
Route::get('/article/fetch/{articleId}', 'ApiController@fetchArticleById')->middleware('checkAPIUserToken');

// upvote an article
Route::post('/article/upvote/{articleId}', 'ApiController@upvoteArticle')->middleware('checkAPIUserToken');

// downvote an article
Route::post('/article/downvote/{articleId}', 'ApiController@downvoteArticle')->middleware('checkAPIUserToken');

// increase the number of views of an article
Route::post('/article/increase-views/{articleId}', 'ApiController@increaseArticleViews')->middleware('checkAPIUserToken');

// get the categories of questions
Route::get('/questions/get-categories', 'ApiController@getQuestionCategories')->middleware('checkAPIUserToken');

// get the details of a health resource
Route::get('/health-resources/get-resource/{resourceId}', 'ApiController@getHealthResourceDetails')->middleware('checkAPIUserToken');

// get all health resources
Route::get('/health-resources/fetch', 'ApiController@getAllHealthResources')->middleware('checkAPIUserToken');

// get all doctors
Route::get('/doctors/fetch', 'ApiController@getAllDoctors')->middleware('checkAPIUserToken');

// get the details of a doctor
Route::get('/doctors/fetch-doctor/{doctorId}', 'ApiController@getDoctorDetials')->middleware('checkAPIUserToken');

// get all pharmacies
Route::get('/pharmacies/fetch', 'ApiController@getAllPharmacies')->middleware('checkAPIUserToken');

// get the details of a pharmacy
Route::get('/pharmacies/get/{resourceId}', 'ApiController@getPharmacyDetails')->middleware('checkAPIUserToken');

// get all videos
Route::get('/videos/fetch', 'ApiController@getVideos')->middleware('checkAPIUserToken');

// upvote a video
Route::post('/video/upvote/{videoId}', 'ApiController@upvoteVideo')->middleware('checkAPIUserToken');

// downvote a video
Route::post('/video/downvote/{videoId}', 'ApiController@downvoteVideo')->middleware('checkAPIUserToken');

// increase the views of a video
Route::post('/video/increase-views/{videoId}', 'ApiController@increaseVideoViews')->middleware('checkAPIUserToken');
