<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacilitatorDashboardController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\FacilitatorResourceController;
use App\Http\Controllers\Facilitator\FacilitatorMessageController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\PersonnelDashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PersonnelResourceController;
use App\Http\Controllers\PersonnelAssessmentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDepartmentController;
use App\Http\Controllers\AdminFacilitatorController;
use App\Http\Controllers\AdminPersonnelController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminPersonnelPerformanceController;
use App\Http\Controllers\FacilitatorProgressController;
use App\Http\Controllers\FacilitatorMessageHistoryController;
use App\Http\Controllers\PersonnelEnrollmentController;
use App\Http\Controllers\FacilitatorCourseController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminMagazineController;
use App\Http\Controllers\Admin\AdminWorkshopController;
use App\Http\Controllers\Admin\AdminSymposiumController;
use App\Http\Controllers\Admin\AdminPracticalGuideController;
use App\Http\Controllers\Admin\AdminMeetingController;
use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminHelpCenterController;
use App\Http\Controllers\Admin\AdminForumController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MagazineController;
use App\Http\Controllers\WorkshopController;
use App\Http\Controllers\SymposiumController;
use App\Http\Controllers\PracticalGuideController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\ForumController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
Route::get('/departments/{department}/resources', [DepartmentController::class, 'resources'])->name('departments.resources.index');
Route::get('/resource-types', [ResourceController::class, 'types'])->name('resource.types');
Route::get('/trainings', [TrainingController::class, 'index'])->name('trainings.index');

// Content Management Routes (Public) - Must come before resource routes
Route::prefix('resources')->group(function () {
    Route::get('/library', function () { return view('pages.resources.library'); })->name('resources.library');
    Route::get('/courses', function () { return view('pages.resources.courses'); })->name('resources.courses');
    Route::get('/training-fundamentals', function () { return view('pages.resources.training-fundamentals'); })->name('resources.training-fundamentals');
    Route::get('/tools', function () { return view('pages.resources.tools'); })->name('resources.tools');
});

Route::resource('resources', ResourceController::class)->only(['index', 'show']);
Route::get('/resources/{resource}/file', [ResourceController::class, 'getFile'])->name('resources.file');
Route::post('/resources/{resource}/enroll', [ResourceController::class, 'enroll'])->name('resources.enroll');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Admin dedicated login (email/password only)
    Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'adminLogin']);
    // Resend department access code (user provides email + dept)
    Route::post('/department-code/resend', [LoginController::class, 'resendDepartmentCode'])->name('department.resend');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/department-login', [LoginController::class, 'departmentLogin'])->name('login.department');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Enroll route for resources
    Route::post('/resources/{resource}/enroll', [ResourceController::class, 'enroll'])->name('resources.enroll');

    // Help routes
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::post('/help/send', [HelpController::class, 'send'])->name('help.send');
    Route::post('/help/settings', [HelpController::class, 'updateSettings'])->name('help.settings.update');

    // Facilitator routes
    Route::middleware('role:facilitator')->prefix('facilitator')->name('facilitator.')->group(function () {
        Route::get('/dashboard', [FacilitatorDashboardController::class, 'index'])->name('dashboard');

        // Personnel routes
        Route::resource('personnel', PersonnelController::class);
        Route::get('personnel/{personnel}/progress', [FacilitatorProgressController::class, 'index'])->name('personnel.progress');

    // Facilitator messaging (group or personal)
    Route::post('/messages', [FacilitatorMessageController::class, 'store'])->name('messages.store');
    
    // Message history routes
    Route::prefix('messages')->name('messages.')->group(function() {
        Route::get('/', [FacilitatorMessageHistoryController::class, 'index'])->name('index');
        Route::get('/create', [FacilitatorMessageHistoryController::class, 'create'])->name('create');
        Route::get('/{message}', [FacilitatorMessageHistoryController::class, 'show'])->name('show');
    });

        // Resources routes
        Route::prefix('resources')->name('resources.')->group(function() {
            Route::get('/videos', [FacilitatorResourceController::class, 'videos'])->name('videos');
            Route::get('/ebooks', [FacilitatorResourceController::class, 'ebooks'])->name('ebooks');
            Route::get('/audios', [FacilitatorResourceController::class, 'audios'])->name('audios');
            Route::get('/images', [FacilitatorResourceController::class, 'images'])->name('images');
            Route::get('/podcasts', [FacilitatorResourceController::class, 'podcasts'])->name('podcasts');
        });
        Route::resource('resources', FacilitatorResourceController::class);

        // Assessment routes
        Route::get('assessments/submissions', [AssessmentController::class, 'submissions'])->name('assessments.submissions');
        Route::resource('assessments', AssessmentController::class);
        Route::post('assessments/{submission}/grade', [AssessmentController::class, 'grade'])->name('assessments.grade');
        
        // Course management routes
        Route::get('courses', [FacilitatorCourseController::class, 'index'])->name('courses.index');
        Route::get('courses/create', [FacilitatorCourseController::class, 'create'])->name('courses.create');
        Route::post('courses', [FacilitatorCourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{course}', [FacilitatorCourseController::class, 'show'])->name('courses.show');
        Route::get('courses/{course}/edit', [FacilitatorCourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [FacilitatorCourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [FacilitatorCourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('courses/{course}/file', [FacilitatorCourseController::class, 'getFile'])->name('courses.file');
        Route::get('courses/{course}/stream', [FacilitatorCourseController::class, 'streamVideo'])->name('courses.stream');
    });

    // Personnel routes
    Route::middleware('role:personnel')->prefix('personnel')->name('personnel.')->group(function () {
        Route::get('/dashboard', [PersonnelDashboardController::class, 'index'])->name('dashboard');
        Route::get('/instructions', [PersonnelDashboardController::class, 'instructions'])->name('instructions');
        Route::get('/courses', [PersonnelDashboardController::class, 'courses'])->name('courses');

        // Training routes (admin-controlled)
        Route::prefix('trainings')->name('trainings.')->group(function() {
            Route::get('/', [PersonnelTrainingController::class, 'index'])->name('index');
            Route::get('/guidelines', [PersonnelTrainingController::class, 'guidelines'])->name('guidelines');
            Route::get('/seminars', [PersonnelTrainingController::class, 'seminars'])->name('seminars');
            Route::get('/{training}', [PersonnelTrainingController::class, 'show'])->name('show');
            Route::get('/{training}/file', [PersonnelTrainingController::class, 'getFile'])->name('file');
        });

        // Resources routes
        Route::prefix('resources')->name('resources.')->group(function() {
            Route::get('/', [PersonnelResourceController::class, 'index'])->name('index');
            Route::get('/{resource}', [PersonnelResourceController::class, 'show'])->name('show');
            Route::get('/{resource}/file', [PersonnelResourceController::class, 'getFile'])->name('file');
            Route::get('/{resource}/stream', [PersonnelResourceController::class, 'streamVideo'])->name('stream');
        });

        // Enrollment routes
        Route::post('/enroll', [PersonnelEnrollmentController::class, 'store'])->name('enroll.store');

        Route::resource('assessments', PersonnelAssessmentController::class)->only(['index', 'show', 'store']);
        Route::get('/assessments/results', [PersonnelAssessmentController::class, 'results'])->name('assessments.results');
    });

    // Admin routes
    // Use fully-qualified middleware class with parameter to avoid alias-resolution issues
    Route::middleware([\App\Http\Middleware\Role::class . ':secondary_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('departments', AdminDepartmentController::class);
        Route::resource('facilitators', AdminFacilitatorController::class);
        Route::resource('personnel', AdminPersonnelController::class);
        Route::resource('resources', AdminResourceController::class);
        
        // Unified User Management
        Route::resource('users', AdminUserController::class);
        Route::patch('/users/{user}/change-role', [AdminUserController::class, 'changeRole'])->name('users.change-role');
        Route::patch('/users/{user}/change-avatar', [AdminUserController::class, 'changeAvatar'])->name('users.change-avatar');
        
        // Admin messaging routes
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/create', [AdminMessageController::class, 'create'])->name('messages.create');
        Route::post('/messages', [AdminMessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
        
        // Personnel performance routes
        Route::get('/personnel-performance', [AdminPersonnelPerformanceController::class, 'index'])->name('personnel-performance.index');
        Route::get('/personnel-performance/{user}', [AdminPersonnelPerformanceController::class, 'show'])->name('personnel-performance.show');
        
        // Settings routes
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        
        // Analytics routes
        Route::get('/analytics', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        Route::get('/analytics/api', [AnalyticsController::class, 'dashboardApi'])->name('analytics.api');
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard.view');
        Route::get('/analytics/user-activity', [AnalyticsController::class, 'userActivity'])->name('analytics.user_activity');
        Route::get('/analytics/course-engagement', [AnalyticsController::class, 'courseEngagement'])->name('analytics.course_engagement');
        Route::get('/analytics/platform-usage', [AnalyticsController::class, 'platformUsage'])->name('analytics.platform_usage');
        Route::get('/analytics/system-performance', [AnalyticsController::class, 'systemPerformance'])->name('analytics.system_performance');
        Route::post('/analytics/track', [AnalyticsController::class, 'trackEvent'])->name('analytics.track');
        Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
        
        // Blog Management
        Route::resource('blog', AdminBlogController::class);
        Route::delete('/blog/{blog}/image/{index}', [AdminBlogController::class, 'deleteImage'])->name('blog.image.delete');
        
        // News Management
        Route::resource('news', AdminNewsController::class);
        
        // Magazine Management
        Route::resource('magazine', AdminMagazineController::class);
        
        // Workshops Management
        Route::resource('workshops', AdminWorkshopController::class);
        Route::post('/workshops/{workshop}/attendee', [AdminWorkshopController::class, 'addAttendee'])->name('workshops.attendee.add');
        
        // Symposiums Management
        Route::resource('symposiums', AdminSymposiumController::class);
        
        // Practical Guides Management
        Route::resource('guides', AdminPracticalGuideController::class);
        
        // Meetings Management
        Route::resource('meetings', AdminMeetingController::class);
        
        // Calendar Management
        Route::resource('calendar', AdminCalendarController::class);
        Route::get('/calendar/events/json', [AdminCalendarController::class, 'getEvents'])->name('calendar.events.json');
        
        // Help Center Management
        Route::resource('help-center', AdminHelpCenterController::class);
        Route::get('/help-center/{helpCenter}/feedbacks', [AdminHelpCenterController::class, 'feedbacks'])->name('help-center.feedbacks');
        
        // Forum Management
        Route::get('/forum/categories', [AdminForumController::class, 'categories'])->name('forum.categories');
        Route::get('/forum/categories/create', [AdminForumController::class, 'createCategory'])->name('forum.categories.create');
        Route::post('/forum/categories', [AdminForumController::class, 'storeCategory'])->name('forum.categories.store');
        Route::get('/forum/categories/{category}/edit', [AdminForumController::class, 'editCategory'])->name('forum.categories.edit');
        Route::put('/forum/categories/{category}', [AdminForumController::class, 'updateCategory'])->name('forum.categories.update');
        Route::delete('/forum/categories/{category}', [AdminForumController::class, 'destroyCategory'])->name('forum.categories.destroy');
        Route::get('/forum/threads', [AdminForumController::class, 'threads'])->name('forum.threads');
        Route::get('/forum/threads/{thread}', [AdminForumController::class, 'showThread'])->name('forum.threads.show');
        Route::post('/forum/threads/{thread}/pin', [AdminForumController::class, 'pinThread'])->name('forum.threads.pin');
        Route::post('/forum/threads/{thread}/unpin', [AdminForumController::class, 'unpinThread'])->name('forum.threads.unpin');
        Route::post('/forum/threads/{thread}/lock', [AdminForumController::class, 'lockThread'])->name('forum.threads.lock');
        Route::post('/forum/threads/{thread}/unlock', [AdminForumController::class, 'unlockThread'])->name('forum.threads.unlock');
        Route::delete('/forum/threads/{thread}', [AdminForumController::class, 'destroyThread'])->name('forum.threads.destroy');
        Route::get('/forum/posts', [AdminForumController::class, 'posts'])->name('forum.posts');
        Route::post('/forum/posts/{post}/moderate', [AdminForumController::class, 'moderatePost'])->name('forum.posts.moderate');
        Route::delete('/forum/posts/{post}', [AdminForumController::class, 'destroyPost'])->name('forum.posts.destroy');
        
        // Ratings Management
        Route::get('/ratings', [RatingController::class, 'adminIndex'])->name('ratings.index');
        Route::post('/ratings/{rating}/approve', [RatingController::class, 'approve'])->name('ratings.approve');
        Route::post('/ratings/{rating}/reject', [RatingController::class, 'reject'])->name('ratings.reject');
        Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    });
    
    // Ratings routes (authenticated users)
    Route::middleware('auth')->prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', [RatingController::class, 'index'])->name('user.index');
        Route::get('/create', [RatingController::class, 'create'])->name('create');
        Route::post('/', [RatingController::class, 'store'])->name('store');
        Route::get('/{user}', [RatingController::class, 'show'])->name('show');
    });
});

// Content Management Routes (Public)
Route::prefix('assessments')->group(function () {
    Route::get('/assignments', function () { return view('pages.assessments.assignments'); })->name('assessments.assignments');
    Route::get('/performance-tracker', function () { return view('pages.assessments.performance-tracker'); })->name('assessments.performance-tracker');
    Route::get('/ratings-points', function () { return view('pages.assessments.ratings-points'); })->name('assessments.ratings-points');
    Route::get('/charts', function () { return view('pages.assessments.charts'); })->name('assessments.charts');
});

Route::prefix('training')->group(function () {
    Route::get('/workshops', [WorkshopController::class, 'index'])->name('training.workshops');
    Route::get('/workshops/{workshop}', [WorkshopController::class, 'show'])->name('training.workshops.show');
    Route::get('/symposium', [SymposiumController::class, 'index'])->name('training.symposium');
    Route::get('/symposium/{symposium}', [SymposiumController::class, 'show'])->name('training.symposium.show');
    Route::get('/practical-guides', [PracticalGuideController::class, 'index'])->name('training.practical-guides');
    Route::get('/practical-guides/{guide}', [PracticalGuideController::class, 'show'])->name('training.guides.show');
});

Route::prefix('events')->group(function () {
    Route::get('/meetings', [EventController::class, 'meetings'])->name('events.meetings');
    Route::get('/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('/calendar/json', [EventController::class, 'calendarJson'])->name('events.calendar.json');
    Route::get('/reminders', [EventController::class, 'reminders'])->name('events.reminders');
});

Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/latest-posts', [BlogController::class, 'latestPosts'])->name('blog.latest-posts');
    Route::get('/categories', [BlogController::class, 'categories'])->name('blog.categories');
    Route::get('/archive', [BlogController::class, 'archive'])->name('blog.archive');
    Route::get('/{blog}', [BlogController::class, 'show'])->name('blog.show');
});

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/latest', [NewsController::class, 'latest'])->name('news.latest');
    Route::get('/categories', [NewsController::class, 'categories'])->name('news.categories');
    Route::get('/multimedia', [NewsController::class, 'multimedia'])->name('news.multimedia');
    Route::get('/{news}', [NewsController::class, 'show'])->name('news.show');
});

Route::prefix('magazine')->group(function () {
    Route::get('/', [MagazineController::class, 'index'])->name('magazine.index');
    Route::get('/current-issue', [MagazineController::class, 'currentIssue'])->name('magazine.current-issue');
    Route::get('/archives', [MagazineController::class, 'archives'])->name('magazine.archives');
    Route::get('/company-stories', [MagazineController::class, 'companyStories'])->name('magazine.company-stories');
    Route::get('/{magazine}', [MagazineController::class, 'show'])->name('magazine.show');
});

Route::prefix('about')->group(function () {
    Route::get('/story', function () { return view('pages.about.story'); })->name('about.story');
    Route::get('/mission-values', function () { return view('pages.about.mission-values'); })->name('about.mission-values');
    Route::get('/team', function () { return view('pages.about.team'); })->name('about.team');
});

Route::prefix('help-center')->group(function () {
    Route::get('/search', [HelpCenterController::class, 'search'])->name('help-center.search');
    Route::get('/guides', [HelpCenterController::class, 'guides'])->name('help-center.guides');
    Route::get('/articles/{article}', [HelpCenterController::class, 'show'])->name('help-center.article');
    Route::get('/contact', [HelpCenterController::class, 'contact'])->name('help-center.contact');
    Route::match(['get', 'post'], '/feedback', [HelpCenterController::class, 'feedback'])->name('help-center.feedback');
});

Route::prefix('help-forum')->group(function () {
    Route::get('/topics', [ForumController::class, 'topics'])->name('help-forum.topics');
    Route::get('/threads/{thread}', [ForumController::class, 'show'])->name('help-forum.show');
    Route::get('/create', [ForumController::class, 'create'])->name('help-forum.create')->middleware('auth');
    Route::post('/threads', [ForumController::class, 'store'])->name('help-forum.store')->middleware('auth');
    Route::post('/threads/{thread}/reply', [ForumController::class, 'storePost'])->name('help-forum.reply')->middleware('auth');
    Route::get('/guidelines', [ForumController::class, 'guidelines'])->name('help-forum.guidelines');
    Route::get('/profiles', [ForumController::class, 'profiles'])->name('help-forum.profiles');
    Route::get('/events', [ForumController::class, 'events'])->name('help-forum.events');
});

Route::prefix('support')->group(function () {
    Route::get('/live-chat', function () { return view('pages.support.live-chat'); })->name('support.live-chat');
    Route::get('/email', function () { return view('pages.support.email'); })->name('support.email');
    Route::get('/whatsapp', function () { return view('pages.support.whatsapp'); })->name('support.whatsapp');
});

// Health check routes (public)
Route::get('/health', [HealthCheckController::class, 'health']);
Route::get('/health/metrics', [HealthCheckController::class, 'metrics']);
Route::get('/health/dashboard', [HealthCheckController::class, 'dashboard']);

// If present, include additional auth routes (password reset, email verification) from routes/auth.php
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
