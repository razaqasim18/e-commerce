<?php

use App\Helpers\CartHelper;
use App\Helpers\CustomHelper;
use App\Models\Admin;
use App\Models\Commission;
use App\Models\Point;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

//  commands to run

// composer update
// composer dump-autoload
// php artisan clear-compiled
// php artisan config:cache
// php artisan cache:clear
// php artisan optimize:clear
// php artisan route:clear
// php artisan migrate:fresh --seed
// php artisan queue:work
// php artisan schedule:run
// * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
//*    *    *    *    *    curl -s https://tryptu.digitallinkcard.xyz/public/schedule-run
//*    *    *    *    *    curl -s https://tryptu.digitallinkcard.xyz/public/queue-work

Route::get('/refresh-csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name("refresh-csrf-token");

Route::get('/custom-notification', function () {
    $admin = Admin::find(1);
    $msg = "New order is placed";
    $type = 4;
    $link = "admin/request/epin/detail/1";
    $detail = "detail detail";
    // $admin->notify(new AdminNotification($msg, $type, $link, $detail));
    $adminnotification = new AdminNotification($msg, $type, $link, $detail);
    Notification::send($admin, $adminnotification);
    echo "done";
});

Route::get('/cache-clear', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    echo "cache-clear";
});

Route::get('/queue-work', function () {
    info("Queue worker executed " . now());
    Artisan::call('queue:work');
    echo "queue-work";
});

Route::get('/schedule-run', function () {
    info("schedule run executed " . now());
    Artisan::call('schedule:run');
    echo "queue-work";
});

Route::get('/calculate-commission/{id}', function ($userid) {
    $childpoints = CustomHelper::calculateChildPoint($userid);
    $childcommision = CustomHelper::calculateCommission($childpoints, $userid);
    if ($childcommision) {
        $wallet = Wallet::updateOrCreate(
            ['user_id' => $userid],
            ['amount' => DB::raw('amount + ' . $childcommision)]
        );
        WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $childcommision,
            'status' => 2,
        ]);
    }
    echo "Points " . $childpoints . "<br/>";
    echo "Commission " . $childcommision . "<br/>";
});

//change user rank
Route::get('/rank/{id}', function ($userid) {
    // $singleUserRankJob = new singleUserRankJob($id);
    // Queue::push($singleUserRankJob);

    $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $userid)->first();
    if ($user) {
        $commission = Commission::all();
        foreach ($commission as $commissionrow) {
            if ($user->point >= $commissionrow->points) {
                Point::updateOrCreate(
                    ['user_id' => $userid],
                    ['commission_id' => $commissionrow->id]
                );
                if ($user->commission_id == null || $user->commission_id < $commissionrow->id) {
                    DB::transaction(function () use ($userid, $commissionrow) {
                        $wallet = Wallet::updateOrCreate(
                            ['user_id' => $userid],
                            ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                        );
                        WalletTransaction::insert([
                            'wallet_id' => $wallet->id,
                            'amount' => $commissionrow->gift,
                            'is_gift' => 1,
                            'status' => 1,
                        ]);
                    });
                }
            }
        }
    }

});

//change all user rank
Route::get('/all/rank', function () {
    $user = User::select('commission_id', 'users.id AS userid', 'point')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
    $commission = Commission::all();
    foreach ($user as $userrow) {
        foreach ($commission as $commissionrow) {
            if ($userrow->point >= $commissionrow->points) {
                Point::updateOrCreate(
                    ['user_id' => $userrow->userid],
                    ['commission_id' => $commissionrow->id]
                );
                if ($userrow->commission_id == null || $userrow->commission_id < $commissionrow->id) {
                    DB::transaction(function () use ($userrow, $commissionrow) {
                        $wallet = Wallet::updateOrCreate(
                            ['user_id' => $userrow->userid],
                            ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                        );
                        WalletTransaction::insert([
                            'wallet_id' => $wallet->id,
                            'amount' => $commissionrow->gift,
                            'is_gift' => 1,
                            'status' => 1,
                        ]);
                    });
                }
            }
        }
    }

});

// check discount
Route::get('/discount', function () {
    $result = CartHelper::cartDiscountCount(Auth::guard('web')->user()->id);
    dd($result);
});

// home pages
Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('welcome');
Route::get('/home', [App\Http\Controllers\FrontController::class, 'index'])->name('home');

//static pages
Route::get('/privacy-policy', [App\Http\Controllers\FrontController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-condition', [App\Http\Controllers\FrontController::class, 'termCondition'])->name('terms.condition');
Route::get('/contact-us', [App\Http\Controllers\FrontController::class, 'contactUs'])->name('contact.us');
Route::get('/about-us', [App\Http\Controllers\FrontController::class, 'aboutUs'])->name('about.us');
Route::get('/success-stories', [App\Http\Controllers\FrontController::class, 'successStories'])->name('success.stories');

//blogs
Route::get('/blogs', [App\Http\Controllers\FrontController::class, 'blogs'])->name('blogs');
Route::get('/blog/{id}', [App\Http\Controllers\FrontController::class, 'blogSingle'])->name('blog.single');

// shop
Route::get('/shop', [App\Http\Controllers\FrontController::class, 'shop'])->name('shop');
Route::get('/shop/search', [App\Http\Controllers\FrontController::class, 'shopSearch'])->name('shop.search');

Route::get('/other-brand', [App\Http\Controllers\FrontController::class, 'otherBrand'])->name('other.brand');
Route::get('/other-brand/search', [App\Http\Controllers\FrontController::class, 'otherBrandSearch'])->name('other.brand.search');

// single product
Route::get('/product/detail/{id}', [App\Http\Controllers\FrontController::class, 'productDetail'])->name('product.detail');
Route::get('/ajax/product/detail/{id}', [App\Http\Controllers\FrontController::class, 'ajaxProductDetail'])->name('ajax.product.detail');

// cart
Route::prefix('/cart')->name('cart.')->group(function () {
    Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
    Route::post('/insert', [App\Http\Controllers\CartController::class, 'insert'])->name('insert');
    Route::post('/insert/discount', [App\Http\Controllers\CartController::class, 'insertDiscount'])->name('insert.discount');
    Route::post('/update', [App\Http\Controllers\CartController::class, 'update'])->name('update');
    Route::post('/delete', [App\Http\Controllers\CartController::class, 'delete'])->name('delete');
    Route::get('/list', [App\Http\Controllers\CartController::class, 'ajaxList'])->name('list');
    Route::get('/discount/{id}', [App\Http\Controllers\CartController::class, 'discount'])->name('discount');
});

Route::prefix('/request/epin')->name('request.epin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Auth\EpinController::class, 'loadEpinRequest'])->name('load');
    Route::post('/save', [App\Http\Controllers\Auth\EpinController::class, 'saveEpinRequest'])->name('save');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth:web', 'verified', 'isblocked', 'isdeleted'])->group(function () {

    // checkout
    Route::get('/checkout', [App\Http\Controllers\FrontController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\FrontController::class, 'checkoutProcess'])->name('checkout.process');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    // ticket
    Route::prefix('/ticket')->name('ticket.')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('list');
        Route::get('/add', [App\Http\Controllers\TicketController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\TicketController::class, 'insert'])->name('insert');
        Route::delete('/delete/{id}', [App\Http\Controllers\TicketController::class, 'delete'])->name('delete');
        Route::get('/detail/{id}', [App\Http\Controllers\TicketController::class, 'detail'])->name('detail');
        Route::get('/reply/{id}', [App\Http\Controllers\TicketController::class, 'reply'])->name('reply');
        Route::post('/reply/insert/{id}', [App\Http\Controllers\TicketController::class, 'replyInsert'])->name('reply.insert');

    });

    //balance
    Route::prefix('/balance')->name('balance.')->group(function () {
        Route::get('/history', [App\Http\Controllers\BalanceController::class, 'history'])->name('history');
        Route::get('/add', [App\Http\Controllers\BalanceController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\BalanceController::class, 'insert'])->name('insert');
        Route::get('/detail/{id}', [App\Http\Controllers\BalanceController::class, 'detail'])->name('detail');
    });

    //withdraw
    Route::prefix('/withdraw')->name('withdraw.')->group(function () {
        Route::get('/history', [App\Http\Controllers\WithdrawController::class, 'history'])->name('history');
        Route::get('/add', [App\Http\Controllers\WithdrawController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\WithdrawController::class, 'insert'])->name('insert');
        Route::get('/detail/{id}', [App\Http\Controllers\WithdrawController::class, 'detail'])->name('detail');
    });

    // payment information
    Route::prefix('/payment/information')->name('payment.information.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'paymentInformationLoad'])->name('load');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'paymentInformationUpdate'])->name('update');
    });

    //profile
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'profileLoad'])->name('load');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'profileUpdate'])->name('update');
    });

    //password
    Route::prefix('/password')->name('password.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'passwordLoad'])->name('load');
        Route::post('/user/update', [App\Http\Controllers\ProfileController::class, 'passwordUpdate'])->name('user.update');
    });

    //team genealogy
    Route::prefix('/team')->name('team.')->group(function () {
        Route::get('/index/{id?}', [App\Http\Controllers\TeamController::class, 'index'])->name('list');
    });

    // order
    Route::prefix('/order')->name('order.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/detail/{id}', [App\Http\Controllers\OrderController::class, 'detail'])->name('detail');
        Route::get('/print/pdf/{id}', [App\Http\Controllers\OrderController::class, 'printPDF'])->name('print.pdf');
        Route::get('/cancel/{id}', [App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');
    });

});

//admin
Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showAdminLoginForm'])->name('login.view');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'adminLogin'])->name('login.submit');

    // logout
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'adminLogout'])->name('logout');

    Route::middleware(['auth:admin'])->group(function () {
        //profile
        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [App\Http\Controllers\Admin\AdminController::class, 'profileUpdate'])->name('profile.update');
        Route::get('/password', [App\Http\Controllers\Admin\AdminController::class, 'passwordLoad'])->name('password');
        Route::post('/password/updates', [App\Http\Controllers\Admin\AdminController::class, 'passwordUpdate'])->name('password.updates');

        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home');
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

        //notification
        Route::prefix('/notification')->name('notification.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'allNotifications'])->name('list');
            Route::get('/unread', [App\Http\Controllers\Admin\HomeController::class, 'unreadNotifications'])->name('unread');
            Route::post('/mark/read', [App\Http\Controllers\Admin\HomeController::class, 'readNotifications'])->name('read');
        });

        // ticket
        Route::prefix('/ticket')->name('ticket.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('list');
            Route::get('/status/{id}', [App\Http\Controllers\Admin\TicketController::class, 'changeStatus'])->name('status');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\TicketController::class, 'delete'])->name('delete');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\TicketController::class, 'detail'])->name('detail');
            Route::get('/reply/{id}', [App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('reply');
            Route::post('/reply/insert/{id}', [App\Http\Controllers\Admin\TicketController::class, 'replyInsert'])->name('reply.insert');
        });

        // user
        Route::prefix('/client')->name('client.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('list');
            Route::get('/log', [App\Http\Controllers\Admin\UserController::class, 'log'])->name('log');
            Route::get('/add', [App\Http\Controllers\Admin\UserController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\UserController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\UserController::class, 'detail'])->name('detail');
            Route::get('/block/{id}/{staus}', [App\Http\Controllers\Admin\UserController::class, 'block'])->name('block');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('delete');
            Route::post('/add/point', [App\Http\Controllers\Admin\UserController::class, 'insertPoint'])->name('insert.point');
        });

        // commission
        Route::prefix('/commission')->name('commission.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\CommissionController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\CommissionController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'delete'])->name('delete');
        });

        // category
        Route::prefix('/category')->name('category.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\CategoryController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\CategoryController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('delete');
        });

        // blog
        Route::prefix('/blog')->name('blog.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BlogController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BlogController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BlogController::class, 'delete'])->name('delete');
        });

        // brand
        Route::prefix('/brand')->name('brand.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BrandController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BrandController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BrandController::class, 'delete'])->name('delete');
        });

        // product
        Route::prefix('/product')->name('product.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\ProductController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\ProductController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('delete');
            Route::delete('/delete/media/{productid}/{mediaid}', [App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('delete.media');
        });

        // order
        Route::prefix('/order')->name('order.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\OrderController::class, 'detail'])->name('detail');
            Route::get('/print/pdf/{id}', [App\Http\Controllers\Admin\OrderController::class, 'printPDF'])->name('print.pdf');
            Route::get('/change/{status}/{id}', [App\Http\Controllers\Admin\OrderController::class, 'changeStatus'])->name('change.status');
            Route::post('/approve', [App\Http\Controllers\Admin\OrderController::class, 'orderApprove'])->name('insert');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\OrderController::class, 'delete'])->name('delete');
        });

        //bank
        Route::prefix('/bank')->name('bank.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BankController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BankController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BankController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BankController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BankController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BankController::class, 'delete'])->name('delete');
        });

        //business account
        Route::prefix('/business/account')->name('business.account.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BusinessAccountController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BusinessAccountController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BusinessAccountController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'delete'])->name('delete');
        });

        //success
        Route::prefix('/success/story')->name('success.story.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SuccessStoryController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\SuccessStoryController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\SuccessStoryController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'delete'])->name('delete');
        });

        //setting
        Route::prefix('/setting')->name('setting.')->group(function () {

            Route::get('/site', [App\Http\Controllers\Admin\SettingController::class, 'loadSiteSetting'])->name('site');
            Route::post('/site/save', [App\Http\Controllers\Admin\SettingController::class, 'saveSiteSetting'])->name('site.save');

            Route::get('/charges', [App\Http\Controllers\Admin\SettingController::class, 'loadChargesSetting'])->name('charges');
            Route::post('/charges/save', [App\Http\Controllers\Admin\SettingController::class, 'saveChargesSetting'])->name('charges.save');

            Route::get('/banner', [App\Http\Controllers\Admin\SettingController::class, 'bannerSetting'])->name('banner');
            Route::post('/banner/save', [App\Http\Controllers\Admin\SettingController::class, 'saveBannerSetting'])->name('banner.save');
            Route::delete('/banner/delete/media/{productid}', [App\Http\Controllers\Admin\SettingController::class, 'deleteMedia'])->name('delete.media');

        });

        //request
        Route::prefix('/request')->name('request.')->group(function () {

            Route::prefix('/epin')->name('epin.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'epinList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'epinDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'epinChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'epinDelete'])->name('delete');
            });

            Route::prefix('/balance')->name('balance.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'balanceList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'balanceChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceDelete'])->name('delete');
                Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceRemark'])->name('remark');

            });

            Route::prefix('/withdraw')->name('withdraw.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'withdrawList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'withdrawChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawDelete'])->name('delete');
                Route::post('/approve', [App\Http\Controllers\Admin\RequestController::class, 'withdrawApprove'])->name('approve');
                Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawRemark'])->name('remark');
            });

            Route::prefix('/get')->name('get.')->group(function () {
                Route::get('/user/payment/information/{id}', [App\Http\Controllers\Admin\RequestController::class, 'getUserPaymentInformation'])->name('user.payment.information');
            });

        });

    });
});
