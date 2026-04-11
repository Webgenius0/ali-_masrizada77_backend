<?php

use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FirebaseTokenController;
use App\Http\Controllers\Api\Frontend\categoryController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Frontend\ImageController;
use App\Http\Controllers\Api\Frontend\PageController;
use App\Http\Controllers\Api\Frontend\PostController;
use App\Http\Controllers\Api\Frontend\SubcategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Frontend\SettingsController;
use App\Http\Controllers\Api\Frontend\DocumentationRequestController;
use App\Http\Controllers\Api\Frontend\SocialLinksController;
use App\Http\Controllers\Api\Frontend\SubscriberController;
use App\Http\Controllers\Api\PrayerTimesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LegalCMSApiController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\SlugCategoryApiController;
use App\Http\Controllers\Api\NVPlatformOverviewController;
use App\Http\Controllers\Api\ConversationalAIController;
use App\Http\Controllers\Api\Email_text_ai_ResponceController;
use App\Http\Controllers\Api\DriveThruApiController;
use App\Http\Controllers\Api\AboutUsApiController;
use App\Http\Controllers\Api\InfrastructureController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\GetinTouchController;
use App\Http\Controllers\Api\HealthcareApiController;
use App\Http\Controllers\Api\EnergyandUtilityController;
use App\Http\Controllers\Api\GovermentController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ApplyJobController;
use App\Http\Controllers\Api\FastFoodAndTermninalController;
use App\Http\Controllers\Api\FinancialServicesController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\Hedding_blogController;
use App\Http\Controllers\Api\Get_A_FreeController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\LogoController;
use App\Http\Controllers\Api\TrustApiController;
use App\Http\Controllers\Api\FooterController as FooterApiController;
use App\Http\Controllers\Api\AnnouncementApiController;
use App\Http\Controllers\Api\TrustFormApiController;
use App\Http\Controllers\Api\HeddingTrustController;


Route::get('/footer-content', [FooterApiController::class, 'getFooterContent']);



// get  trem and privact data
// Alada alada route
Route::get('/privacy-policy', [LegalCMSApiController::class, 'getPrivacyPolicy']);
Route::get('/terms-conditions', [LegalCMSApiController::class, 'getTermsConditions']);




// rayhan add ----------------------------------------------



// FAQ API Route
Route::get('/faqs', [FaqController::class, 'index']);
//---------------------------------






Route::get('/home/intro', [HomePageController::class, 'getHomeIntro']);

//category 1 -3 get data

Route::get('/cms-data/{slug}', [SlugCategoryApiController::class, 'getCmsDataBySlug']);


//NV Platform Overview pages
Route::get('/nvplatform-overview', [NVPlatformOverviewController::class, 'getPlatformOverview']);



//conversational pages
Route::get('/conversational', [ConversationalAIController::class, 'getConversationalContent']);

//conversational pages   instant_support
Route::get('/instant_support', [ConversationalAIController::class, 'instant_support']);


//Email_AI_Response text
Route::get('/email-textai-responce', [Email_text_ai_ResponceController::class, 'email_text_ai_responce']);
//



//Drive Thrue AI
Route::get('/drive-thru', [DriveThruApiController::class, 'getCMSContent']);

//about us page
Route::get('/about-us-content', [AboutUsApiController::class, 'getCMSContent']);





// টাইপ অনুযায়ী ব্লগ লিস্ট পাওয়ার API
Route::get('/blogs', [BlogController::class, 'index']);

Route::get('/blogs-suggestions', [BlogController::class, 'suggestions']);

// সিঙ্গেল ব্লগ দেখার API
Route::get('/blog/details/{id}', [BlogController::class, 'show']);




// deployment model  API Route
Route::get('/deployment', [InfrastructureController::class, 'getInfrastructureData']);


//became a partner

Route::get('/become-a-partner', [PartnerController::class, 'getPartnerData']);

//Health care
Route::get('/healthcare-content', [HealthcareApiController::class, 'getHealthcareContent']);

//energy and Utilitis
Route::get('/energy-content', [EnergyandUtilityController::class, 'getHealthcareContent']);
//Goverment
Route::get('/goverment-content', [GovermentController::class, 'getGovermentContent']);

//fastFood And terminals
Route::get('/fastfood-content', [FastFoodAndTermninalController::class, 'getFastfoodContent']);
//Finacial Services
Route::get('/financial-content', [FinancialServicesController::class, 'getFinancialContent']);


//career page API
Route::get('/career-page', [CareerController::class, 'getCareerData']);

//Trust page API
Route::get('/trust-content', [TrustApiController::class, 'getFullTrustContent']);

// Trust Form Options API
Route::get('/trust-form-options', [TrustFormApiController::class, 'getOptions']);


//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''


//logo show
Route::get('/logoshow', [LogoController::class, 'showlogo']);


//show get in touch1 1 data
Route::get('/getintouch1-1data', [GetinTouchController::class, 'getContactSidebar']);

//get in touch 1 contact store
Route::post('/getintouch1-submit', [ContactController::class, 'storeContact']);
//get in touch 1 contact store
Route::post('/getfree-demo-submit', [Get_A_FreeController::class, 'GetFreeDemo']);

//Applyjob content
Route::get('/get-Applyjob-content', [ApplyJobController::class, 'getApplyjobSidebar']);

//apply job
Route::get('/job-positions', [JobApplicationController::class, 'getPositions']);
Route::post('/job-apply', [JobApplicationController::class, 'store']);

//blog hedding content
Route::get('/blog-hedding', [Hedding_blogController::class, 'getBlogContent']);
//trust heding content
Route::get('/trust-heading', [HeddingTrustController::class, 'getTrustContent']);

//document request
Route::post('/documentation-request', [DocumentationRequestController::class, 'store']);
//award data
Route::get('/award-announcement', [AnnouncementApiController::class, 'getAnnouncement']);


//'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
// ------------------------------------------------------rayhan    ------------------------------------- end --------------------------



//page
Route::get('/page/home', [HomeController::class, 'index']);
Route::get('/category', [categoryController::class, 'index']);
Route::get('/subcategory', [SubcategoryController::class, 'index']);
Route::get('/social/links', [SocialLinksController::class, 'index']);
Route::get('/settings', [SettingsController::class, 'index']);
Route::get('/faq', [FaqController::class, 'index']);
Route::post('subscriber/store',[SubscriberController::class, 'store'])->name('api.subscriber.store');

/*
# Post
*/
Route::middleware(['auth:api'])->controller(PostController::class)->prefix('auth/post')->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::post('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::get('/job_posts', [PostController::class, 'post']);
Route::get('/job_post/show/{id}', [PostController::class, 'show']);
Route::get('/post_carrer', [PostController::class, 'post_carrer']);

Route::middleware(['auth:api'])->controller(ImageController::class)->prefix('auth/post/image')->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/delete/{id}', 'destroy');
});

Route::get('dynamic/page', [PageController::class, 'index']);
Route::get('dynamic/page/show/{slug}', [PageController::class, 'show']);

/*
# Auth Route
*/

Route::group(['middleware' => 'guest:api'], function ($router) {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    Route::post('/verify-otp', [RegisterController::class, 'VerifyEmail']);

    //login
    Route::post('login', [LoginController::class, 'login'])->name('api.login');
    //forgot password
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/otp-token', [ResetPasswordController::class, 'MakeOtpToken']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
    //social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
});

Route::group(['middleware' => ['auth:api', 'api-otp']], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/account/switch', [UserController::class, 'accountSwitch']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-avatar', [UserController::class, 'updateAvatar']);
    Route::delete('/delete-profile', [UserController::class, 'destroy']);
});

/*
# Firebase Notification Route
*/

Route::middleware(['auth:api'])->controller(FirebaseTokenController::class)->prefix('firebase')->group(function () {
    Route::get("test", "test");
    Route::post("token/add", "store");
    Route::post("token/get", "getToken");
    Route::post("token/delete", "deleteToken");
});

/*
# In App Notification Route
*/

Route::middleware(['auth:api'])->controller(NotificationController::class)->prefix('notify')->group(function () {
    Route::get('test', 'test');
    Route::get('/', 'index');
    Route::get('status/read/all', 'readAll');
    Route::get('status/read/{id}', 'readSingle');
});

/*
# Chat Route
*/

Route::middleware(['auth:api'])->controller(ChatController::class)->prefix('auth/chat')->group(function () {
    Route::get('/list', 'list');
    Route::post('/send/{receiver_id}', 'send');
    Route::get('/conversation/{receiver_id}', 'conversation');
    Route::get('/room/{receiver_id}', 'room');
    Route::get('/search', 'search');
    Route::get('/seen/all/{receiver_id}', 'seenAll');
    Route::get('/seen/single/{chat_id}', 'seenSingle');
});

/*
# CMS
*/

Route::prefix('cms')->name('cms.')->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
});

/*
# prayer time
# http:://127.0.0.1:8000/api/prayer-times?date=2025-12-25&lat=23.7018&lng=90.3742&timezone=6&method=1
# http:://127.0.0.1:8000/api/prayer-times/today?lat=23.7018&lng=90.3742&timezone=6&method=1
*/
Route::prefix('prayer-times')->group(function () {
    Route::get('/', [PrayerTimesController::class, 'index']);
    Route::get('/today', [PrayerTimesController::class, 'today']);
    Route::get('/methods', [PrayerTimesController::class, 'methods']);
});



Route::post('contact/store',[ContactController::class, 'store'])->name('contact.store');
