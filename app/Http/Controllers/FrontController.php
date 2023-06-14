<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDiscount;
use App\Models\OrderShippingDetail;
use App\Models\Product;
use App\Notifications\AdminNotification;
use App\Rules\UserOrderGift;
use App\Rules\UserOrderWallet;
use Auth;
use DB;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function index()
    {
        $featureproduct = Product::where('is_feature', 1)->where('is_active', 1)->get();
        $banner = Banner::all();
        $newproduct = Product::orderBy('id', 'DESC')->skip(0)->take(8)->where('is_active', 1)->get();

        return view('welcome', [
            'featureproduct' => $featureproduct,
            'banner' => $banner,
            'newproduct' => $newproduct,
        ]);
    }

    public function checkout(Request $request)
    {
        $city = City::all();
        return view('checkout', compact('city'));
    }

    public function contactUs()
    {
        return view('contact');
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function successStories()
    {
        return view('success_story');
    }

    public function checkoutProcess(Request $request)
    {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'city' => ['required'],
            'street' => ['required'],
            'address' => ['required'],
            'shipping_address' => ['required'],
            'discount' => ['required'],
        ]);

        if ($request->payment_by == '1') { //if user selected payment by wallet
            $this->validate($request, [
                'balance' => ['required', new UserOrderWallet($request->totalpay)],
            ]);
        }

        if ($request->payment_by == '2') { //if user selected payment by cashback
            $this->validate($request, [
                'giftbalance' => ['required', new UserOrderGift($request->totalpay)],
            ]);
        }

        DB::beginTransaction();

        $order_no = CustomHelper::createNewOrderno();
        $order = new Order();
        $order->order_no = $order_no;
        $order->user_id = Auth::guard('web')->user()->id;
        $order->points = $request->subpoint;
        $order->weight = $request->totalweight;
        $order->subtotal = $request->subtotal;
        $order->shippingcharges = $request->shippingcharges;
        $order->total_bill = $request->totalpay;
        $order->discount = $request->discount;
        $order->payment_by = $request->payment_by;
        $orderresponse = $order->save();

        if (\Cart::session('normal')->getContent()->count()) {
            foreach (\Cart::session('normal')->getContent() as $item) {
                $orderdetail[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product' => $item->name,
                    'weight' => $item->attributes->product_weight,
                    'quantity' => $item->quantity,
                    'points' => $item->attributes->product_points,
                    'price' => $item->price,
                ];
                $product = Product::find($item->id);
                $product->stock = $product->stock - $item->quantity;
                if ($product->stock <= 0) {
                    $product->in_stock = 0;
                }
                $product->save();
            }
        }

        if (\Cart::session('discount')->getContent()->count()) {
            foreach (\Cart::session('discount')->getContent() as $item) {
                $orderdetail[] = [
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product' => $item->name,
                    'weight' => $item->attributes->product_weight,
                    'quantity' => $item->quantity,
                    'points' => $item->attributes->product_points,
                    'price' => $item->price,
                ];
                $product = Product::find($item->id);
                $product->stock = $product->stock - $item->quantity;
                if ($product->stock <= 0) {
                    $product->in_stock = 0;
                }
                $product->save();

                $orderdiscount = new OrderDiscount();
                $orderdiscount->user_id = Auth::guard('web')->user()->id;
                $orderdiscount->order_id = $order->id;
                $orderdiscount->product_id = $item->id;
                $orderdiscount->save();
            }
        }

        $orderdetailresponse = OrderDetail::insert($orderdetail);

        $ordershippindetail = new OrderShippingDetail();
        $ordershippindetail->order_id = $order->id;
        $ordershippindetail->name = $request->name;
        $ordershippindetail->email = $request->email;
        $ordershippindetail->phone = $request->phone;
        $ordershippindetail->address = $request->address;
        $ordershippindetail->shipping_address = $request->shipping_address;
        $ordershippindetail->other_information = $request->other;
        $ordershippindetail->city_id = $request->city;
        $ordershippindetail->street = $request->street;
        $ordershippindetailresponse = $ordershippindetail->save();

        $walletresponse = true;
        if ($request->payment_by == '1') { //if user selected payment by wallet
            $walletresponse = CustomHelper::orderWalletTrasection(Auth::guard('web')->user()->id, $request->totalpay);
        }
        if ($request->payment_by == '2') { //if user selected payment by gift
            $walletresponse = CustomHelper::orderWalletGiftTrasection(Auth::guard('web')->user()->id, $request->totalpay);
        }

        if ($orderresponse && $orderdetailresponse && $ordershippindetailresponse && $walletresponse) {
            DB::commit();
            \Cart::session('normal')->clear();
            \Cart::session('discount')->clear();
            // Cart::
            $msg = "New order has been placed";
            $type = 4;
            $link = "admin/order/detail/" . $order->id;
            $detail = "New order is placed with order no#" . $order_no;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);

            Session::forget('coupon_discount');
            return redirect()->route('order.index')->with('success', 'Order Placed Successfully');
        } else {
            DB::rollback();
            return back()->with('error', "Something went wrong");
        }
    }

    public function privacyPolicy()
    {
        return view('privacy_policy');
    }

    public function termCondition()
    {
        return view('term_condition');
    }

    public function productDetail($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::findOrFail($id);
        return view('product_detail', [
            'product' => $product,
        ]);
    }

    public function ajaxProductDetail($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::findOrFail($id);
        return response()->json([
            'product' => $product,
            'productmedia' => $product->getMedia('images'),
        ]);
    }

    public function otherBrand()
    {
        $category = Category::where('is_active', 1)->get();
        $product = Product::where('is_other', 1)->where('is_active', 1)->paginate(9);
        return view('other-brand', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function otherBrandSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = Product::query()->where('is_active', 1);
            if ($request->category) {
                $productquery->where("category_id", $request->category);
            }

            if ($request->has('price')) {
                ($request->price) ? $productquery->orderBy("price", "DESC") : $productquery->orderBy("price", "ASC");
            }
            if ($request->has('sort')) {
                ($request->sort) ? $productquery->orderBy("product", "ASC") : $productquery->orderBy("price", "ASC");
            }
            $productquery->where("is_other", '1');

            $product = $productquery->orderBy('id', 'DESC')->paginate(9);
            $category = Category::where('is_active', 1)->get();
            return view('include.shop', [
                'category' => $category,
                'product' => $product,
            ])->render();
        }
    }

    public function shop()
    {
        $category = Category::where('is_active', 1)->get();
        $product = Product::where('is_other', 0)->where('is_active', 1)->paginate(9);
        return view('shop', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function shopSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = Product::query()->where('is_active', 1);
            if ($request->category) {
                $productquery->where("category_id", $request->category);
            }

            if ($request->has('price')) {
                ($request->price) ? $productquery->orderBy("price", "DESC") : $productquery->orderBy("price", "ASC");
            }
            if ($request->has('sort')) {
                ($request->sort) ? $productquery->orderBy("product", "ASC") : $productquery->orderBy("price", "ASC");
            }
            $productquery->where("is_other", '0');

            $product = $productquery->orderBy('id', 'DESC')->paginate(9);
            $category = Category::where('is_active', 1)->get();
            return view('include.shop', [
                'category' => $category,
                'product' => $product,
            ])->render();
        }
    }

    public function blogs()
    {
        $blog = Blog::where('is_active', 1)->paginate(9);
        return view('blogs', [
            'blog' => $blog,
        ]);
    }

    public function blogSingle($id)
    {
        $blog = Blog::findOrFail($id);
        $blogs = Blog::orderBy('id', 'DESC')->offset(0)->limit(3)->get();
        return view('blog', [
            'blog' => $blog,
            'blogs' => $blogs,
        ]);
    }
}
