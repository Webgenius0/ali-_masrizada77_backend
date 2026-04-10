<?php

use App\Http\Controllers\Web\Backend\Access\PermissionController;
use App\Http\Controllers\Web\Backend\Access\RoleController;
use App\Http\Controllers\Web\Backend\Access\UserController;
use App\Http\Controllers\Web\Backend\AttributeController;
use App\Http\Controllers\Web\Backend\BlogController;
use App\Http\Controllers\Web\Backend\BookingController;
use App\Http\Controllers\Web\Backend\BrandController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\ChatController;
use App\Http\Controllers\Web\Backend\DocumentationRequestController;
use App\Http\Controllers\Web\Backend\CMS\Web\Home\HomeAboutController;
use App\Http\Controllers\Web\Backend\CMS\Web\Home\HomeExampleController;
use App\Http\Controllers\Web\Backend\CMS\Web\Home\HomeIntroController;
use App\Http\Controllers\Web\Backend\ContactController;
use App\Http\Controllers\Web\Backend\CountryController;
use App\Http\Controllers\Web\Backend\CourseController;
use App\Http\Controllers\Web\Backend\CurdController;
use App\Http\Controllers\Web\Backend\CurriculumController;
use App\Http\Controllers\Web\Backend\Settings\FirebaseController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\SettingController;
use App\Http\Controllers\Web\Backend\Settings\SocialController;
use App\Http\Controllers\Web\Backend\Settings\StripeController;
use App\Http\Controllers\Web\Backend\Settings\GoogleMapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\FileManagerController;
use App\Http\Controllers\Web\Backend\ImageController;
use App\Http\Controllers\Web\Backend\LivewireController;
use App\Http\Controllers\Web\Backend\MenuController;
use App\Http\Controllers\Web\Backend\OrderController;
use App\Http\Controllers\Web\Backend\PageController;
use App\Http\Controllers\Web\Backend\PostController;
use App\Http\Controllers\Web\Backend\ProductController;
use App\Http\Controllers\Web\Backend\PropertyController;
use App\Http\Controllers\Web\Backend\Settings\CaptchaController;
use App\Http\Controllers\Web\Backend\Settings\EnvController;
use App\Http\Controllers\Web\Backend\Settings\LogoController;
use App\Http\Controllers\Web\Backend\Settings\OtherController;
use App\Http\Controllers\Web\Backend\Settings\SignatureController;
use App\Http\Controllers\Web\Backend\SocialLinkController;
use App\Http\Controllers\Web\Backend\FooterController;
use App\Http\Controllers\Web\Backend\SubcategoryController;
use App\Http\Controllers\Web\Backend\SubscriberController;
use App\Http\Controllers\Web\Backend\TemplateEmailController;
use App\Http\Controllers\Web\Backend\TransactionController;
use App\Http\Controllers\Web\Backend\QuizController;
use App\Http\Controllers\Web\Backend\ReportController;
use App\Http\Controllers\Web\Backend\VariantController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Web\Backend\CMS\LegalCMSController;
use App\Http\Controllers\Web\Backend\CmsController;
use App\Http\Controllers\Web\Backend\CMS\Web\Home\HomeCmsController;
use App\Http\Controllers\Web\Backend\CMS\Web\NVPlatformOverviewController;
use App\Http\Controllers\Web\Backend\CMS\Web\ConversationalAIController;
use App\Http\Controllers\Web\Backend\CMS\Web\Email_text_ai_ResponceController;
use App\Http\Controllers\Web\Backend\CMS\Web\Drive_ThruAIController;
use App\Http\Controllers\Web\Backend\CMS\Web\InfrastructureController;
use App\Http\Controllers\Web\Backend\CMS\Web\PartnerController;
use App\Http\Controllers\Web\Backend\AboutusController;
use App\Http\Controllers\Web\Backend\CMS\Web\TrustController;
use App\Http\Controllers\Web\Backend\CMS\Web\GetinTouchController;
use App\Http\Controllers\Web\Backend\CMS\Web\ApplyJobController;
use App\Http\Controllers\ElevenLabsController;
use App\Http\Controllers\Web\Backend\CMS\Web\HealthcareController;
use App\Http\Controllers\Web\Backend\CMS\Web\EnergyandUtilitsController;
use App\Http\Controllers\Web\Backend\CMS\Web\GovermentController;
use App\Http\Controllers\Web\Backend\CMS\Web\FastFoodAndTerminalController;
use App\Http\Controllers\Web\Backend\CMS\Web\FinancialServicesController;
use App\Http\Controllers\Web\Backend\JobApplicationController;
use App\Http\Controllers\Web\Backend\Hedding_BlogController;
use App\Http\Controllers\Web\Backend\HeddingTrustController;
use App\Http\Controllers\Web\Backend\CarrerPageController;

Route::get("dashboard", [DashboardController::class, 'index'])->name('dashboard')->middleware(['role:admin|staff']);

Route::group(['middleware' => ['web-admin']], function () {

    Route::controller(MenuController::class)->prefix('menu')->name('menu.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });



Route::prefix('home/getalljob')->name('home.getalljob.')->group(function () {
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('index');
    Route::get('/job-applications/{id}', [JobApplicationController::class, 'show'])->name('show');
    Route::delete('/job-applications/{id}', [JobApplicationController::class, 'destroy'])->name('destroy');
});




    Route::prefix('admin/cms/legal')->name('cms.legal.')->group(function () {
        // Select section & open form
        Route::get('form/{section?}', [LegalCMSController::class, 'form'])->name('form');
        // Store/update
        Route::post('store', [LegalCMSController::class, 'store'])->name('store');
    });







    Route::controller(TemplateEmailController::class)->prefix('template/email')->name('template.email.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(BrandController::class)->prefix('brand')->name('brand.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(CategoryController::class)->prefix('category')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(SubcategoryController::class)->prefix('subcategory')->name('subcategory.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(VariantController::class)->prefix('variant')->name('variant.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(OrderController::class)->prefix('order')->name('order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(BookingController::class)->prefix('booking')->name('booking.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
    });

    Route::controller(PostController::class)->prefix('post')->name('post.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(ImageController::class)->prefix('post/image')->name('post.image.')->group(function () {
        Route::get('/{post_id}', 'index')->name('index');
        Route::get('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::controller(PageController::class)->prefix('page')->name('page.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(SocialLinkController::class)->prefix('social')->name('social.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(FooterController::class)->prefix('footer')->name('footer.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(FaqController::class)->prefix('faq')->name('faq.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::get('subscriber', [SubscriberController::class, 'index'])->name('subscriber.index');

    Route::controller(ContactController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/status/{id}', 'status')->name('status');
        Route::delete('/delete/{id}', 'destroy')->name('destroy'); // নতুন ডিলিট রাউট
    });
//document request show
    Route::controller(DocumentationRequestController::class)->prefix('documentation-request')->name('documentation.request.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::controller(TransactionController::class)->prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/{user_id?}', 'index')->name('index');
        Route::get('/show/{id}', 'show')->name('show');
    });




    /*
    * CMS
    */

    Route::prefix('cms')->name('cms.')->group(function () {

        //Home About
        Route::prefix('home/about')->name('home.about.')->controller(HomeAboutController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');

            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });

        //Home Example
        Route::prefix('home/example')->name('home.example.')->controller(HomeExampleController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/show', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::patch('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}/status', 'status')->name('status');

            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });

        //Home Intro
        Route::prefix('home/intro')->name('home.intro.')->controller(HomeIntroController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');

            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });

        //-------------------------- rayhan adddd-----------------------------------------------

        Route::prefix('admin/home-cms')->name('home.cms.')->group(function () {
            Route::get('/manage/{slug}', [HomeCmsController::class, 'index'])->name('index');
            Route::put('/update/{slug}', [HomeCmsController::class, 'update'])->name('update');
        });



        //NVPlatfromOverview pages
        Route::prefix('home/NVPlatformOverview')->name('home.npoverview.')->controller(NVPlatformOverviewController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });

        //ConversationalAI page data

        Route::prefix('home/ConversationalAI')->name('home.conversational.')->controller(ConversationalAIController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });


        Route::prefix('home/email_and_textai_response')->name('home.emailandtextai.')->controller(Email_text_ai_ResponceController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });
        Route::prefix('home/drive_thruai')->name('home.drive_thruai.')->controller(Drive_ThruAIController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });

        Route::prefix('home/aboutus')->name('home.aboutus.')->controller(AboutusController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::put('/content', 'content')->name('content');
            Route::get('/display', 'display')->name('display');
        });


        Route::prefix('home/infrastructure')->name('home.infrastructure.')->group(function () {
            Route::get('/', [InfrastructureController::class, 'index'])->name('index');
            Route::post('/content', [InfrastructureController::class, 'content'])->name('content');
        });

        Route::prefix('home/partner')->name('home.partner.')->group(function () {
            Route::get('/', [PartnerController::class, 'index'])->name('index');
            Route::post('/content', [PartnerController::class, 'content'])->name('content');
        });
        // Healthcare Page Routes
        Route::prefix('home/healthcare')->name('home.healthcare.')->group(function () {

            Route::get('/', [HealthcareController::class, 'index'])->name('index');

            Route::post('/content', [HealthcareController::class, 'content'])->name('content');
        });

        // Healthcare Page Routes
        Route::prefix('home/energy')->name('home.energy.')->group(function () {

            Route::get('/', [EnergyandUtilitsController::class, 'index'])->name('index');

            Route::post('/content', [EnergyandUtilitsController::class, 'content'])->name('content');
        });

        Route::prefix('home/goverment')->name('home.goverment.')->group(function () {

            Route::get('/', [GovermentController::class, 'index'])->name('index');

            Route::post('/content', [GovermentController::class, 'content'])->name('content');
        });

        Route::prefix('home/fastfood')->name('home.fastfood.')->group(function () {

            Route::get('/', [FastFoodAndTerminalController::class, 'index'])->name('index');

            Route::post('/content', [FastFoodAndTerminalController::class, 'content'])->name('content');
        });

                Route::prefix('home/financail')->name('home.finacial.')->group(function () {

            Route::get('/', [FinancialServicesController::class, 'index'])->name('index');

            Route::post('/content', [FinancialServicesController::class, 'content'])->name('content');
        });

        //carrer pages

                Route::prefix('home/career')->name('home.career.')->group(function () {
            Route::get('/', [CarrerPageController::class, 'index'])->name('index');
            Route::post('/content', [CarrerPageController::class, 'content'])->name('content');
        });


        //------------------------------------------------------------------------------------------------------
        //get in touch 1
        Route::prefix('home/get-in-touch')->name('home.get-in-touch.')->group(function () {
            Route::get('/', [GetinTouchController::class, 'index'])->name('index');
            Route::post('/content', [GetinTouchController::class, 'update'])->name('update');
        });






        //applyjob content CMS content

        Route::prefix('home/applyjob')->name('home.applyjob.')->group(function () {
            Route::get('/', [ApplyJobController::class, 'index'])->name('index');
            Route::post('/content', [ApplyJobController::class, 'update'])->name('update');
        });

        //Trust page
        Route::prefix('home/trust')->name('home.trust.')->group(function () {
            Route::get('/', [TrustController::class, 'index'])->name('index');
            Route::post('/content', [TrustController::class, 'content'])->name('content');
        });


            //Trust Heding content
        Route::prefix('home/trust_heading')->name('home.trust_heading.')->group(function () {
            Route::get('/', [HeddingTrustController::class, 'index'])->name('index');
            Route::post('/content', [HeddingTrustController::class, 'update'])->name('update');
        });


        // checking ai voices syttemm
        Route::prefix('home/test')->name('home.test.')->group(function () {
            // Voice Test Page
            Route::get('/voice-test', [ElevenLabsController::class, 'testVoice'])->name('index');
            // API for Signed URL
            Route::get('/get-signed-url', [ElevenLabsController::class, 'getSignedUrl'])->name('voice.signed_url');
        });

        //--------------------------------rayhanend-------------------------------------------------


    });

    /*
    * Chating Route
    */

    Route::controller(ChatController::class)->prefix('chat')->name('chat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list', 'list')->name('list');
        Route::post('/send/{receiver_id}', 'send')->name('send');
        Route::get('/conversation/{receiver_id}', 'conversation')->name('conversation');
        Route::get('/room/{receiver_id}', 'room');
        Route::get('/search', 'search')->name('search');
        Route::get('/seen/all/{receiver_id}', 'seenAll');
        Route::get('/seen/single/{chat_id}', 'seenSingle');
    });


    /*
    * Users Access Route
    */

    Route::resource('users', UserController::class);
    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/status/{id}', 'status')->name('status');
        Route::get('/new', 'new')->name('new.index');
        Route::get('/ajax/new/count', 'newCount')->name('ajax.new.count');
        Route::get('/card/{slug}', 'card')->name('card');
    });
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);

    /*
    *settings
    */

    //! Route for Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('setting/profile', 'index')->name('setting.profile.index');
        Route::put('setting/profile/update', 'UpdateProfile')->name('setting.profile.update');
        Route::put('setting/profile/update/Password', 'UpdatePassword')->name('setting.profile.update.Password');
        Route::post('setting/profile/update/Picture', 'UpdateProfilePicture')->name('update.profile.picture');
    });

    //! Route for Mail Settings
    Route::controller(MailSettingController::class)->group(function () {
        Route::get('setting/mail', 'index')->name('setting.mail.index');
        Route::patch('setting/mail', 'update')->name('setting.mail.update');

        Route::post('setting/send', 'send')->name('setting.mail.send');
    });

    //! Route for Stripe Settings
    Route::controller(StripeController::class)->prefix('setting/stripe')->name('setting.stripe.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/update', 'update')->name('update');
    });

    //! Route for Firebase Settings
    Route::controller(FirebaseController::class)->prefix('setting/firebase')->name('setting.firebase.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/update', 'update')->name('update');
    });

    //! Route for Environment Settings
    Route::controller(EnvController::class)->group(function () {
        Route::get('setting/env', 'index')->name('setting.env.index');
        Route::patch('setting/env', 'update')->name('setting.env.update');
    });

    //! Route for Firebase Settings
    Route::controller(SocialController::class)->prefix('setting/social')->name('setting.social.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/update', 'update')->name('update');
    });

    //! Route for Stripe Settings
    Route::controller(SettingController::class)->group(function () {
        Route::get('setting/general', 'index')->name('setting.general.index');
        Route::patch('setting/general', 'update')->name('setting.general.update');
    });

    //! Route for Logo Settings
    Route::controller(LogoController::class)->group(function () {
        Route::get('setting/logo', 'index')->name('setting.logo.index');
        Route::patch('setting/logo', 'update')->name('setting.logo.update');
    });

    //! Route for Google Map Settings
    Route::controller(GoogleMapController::class)->group(function () {
        Route::get('setting/google/map', 'index')->name('setting.google.map.index');
        Route::patch('setting/google/map', 'update')->name('setting.google.map.update');
    });

    //! Route for Google Map Settings
    Route::controller(SignatureController::class)->group(function () {
        Route::get('setting/signature', 'index')->name('setting.signature.index');
        Route::patch('setting/signature', 'update')->name('setting.signature.update');
    });

    //! Route for Google Map Settings
    Route::controller(CaptchaController::class)->group(function () {
        Route::get('setting/captcha', 'index')->name('setting.captcha.index');
        Route::patch('setting/captcha', 'update')->name('setting.captcha.update');
    });

    //Ajax settings
    Route::prefix('setting/other')->name('setting.other')->group(function () {
        Route::get('/', [OtherController::class, 'index'])->name('.index');
        Route::get('/mail', [OtherController::class, 'mail'])->name('.mail');
        Route::get('/sms', [OtherController::class, 'sms'])->name('.sms');
        Route::get('/recaptcha', [OtherController::class, 'recaptcha'])->name('.recaptcha');
        Route::get('/pagination', [OtherController::class, 'pagination'])->name('.pagination');
        Route::get('/reverb', [OtherController::class, 'reverb'])->name('.reverb');
        Route::get('/debug', [OtherController::class, 'debug'])->name('.debug');
        Route::get('/access', [OtherController::class, 'access'])->name('.access');
    });

    // Run artisan commands for optimization and cache clearing
    Route::get('/optimize', function () {
        Artisan::call('system:clear-cache');
        return redirect()->back()->with('t-success', 'Message sent successfully');
    })->name('optimize');

    //Filter
    Route::controller(AttributeController::class)->prefix('attribute')->name('attribute.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(PropertyController::class)->prefix('property')->name('property.')->group(function () {
        Route::get('index/{attribute_id?}', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    //address
    Route::controller(CountryController::class)->prefix('location.country')->name('location.country.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');

        Route::get('/status/{id}', 'status')->name('status');

        Route::get('/import', 'import')->name('import');
        Route::get('/export', 'export')->name('export');
    });

    /*
    # Quiz
    */
    Route::controller(QuizController::class)->prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    /*
    # CRUD
    */
    Route::controller(CurdController::class)->prefix('curd')->name('curd.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    /*
    # Blog
    **/
    Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });
    //Bloag Heding content
        Route::prefix('home/blog_heading')->name('blog_heading.')->group(function () {
            Route::get('/', [Hedding_BlogController::class, 'index'])->name('index');
            Route::post('/content', [Hedding_BlogController::class, 'store'])->name('store');
        });

    /*
    # Course
    **/
    Route::controller(CourseController::class)->prefix('course')->name('course.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{id}', 'show')->name('show');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
        Route::get('/status/{id}', 'status')->name('status');
    });

    Route::controller(CurriculumController::class)->prefix('curriculum')->name('curriculum.')->group(function () {
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/delete/{id}', 'destroy')->name('destroy');
    });
});

//livewire
Route::get('livewire/crud', function () {
    return view('backend.layouts.livewire.index');
})->name('livewire.crud.index');

//File-Manager
Route::get('file-manager', [FileManagerController::class, 'index'])->name('file-manager');
Route::post('file-upload', [FileManagerController::class, 'upload']);
Route::delete('file-delete/{file}', [FileManagerController::class, 'delete']);


Route::controller(ReportController::class)->prefix('report')->name('report.')->group(function () {
    Route::get('/users', 'users')->name('users');
});
