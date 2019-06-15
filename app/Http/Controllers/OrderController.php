<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChange;
use App\Mail\OrderMail;
use App\Product;
use Illuminate\Support\Facades\Validator;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PayPal\Api\ShippingAddress;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Transaction;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Amount;
use Illuminate\Support\Facades\Input;
use PayPal\Api\PaymentExecution;
use PayPal\Api\ItemList;
use PayPal\Api\Payment;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\RedirectUrls;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{

    public function __construct()
    {
    /*    $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);*/
    }
    public function show(Request $request, $hash){
        $order = Order::where('hash', $hash)->first();
        if(!$order) return back()->with(['message' => 'Nie znaleziono zamówienia']);
        return view('order.index', compact('order'));
    }
    public function index( Request $request){
        $cart = Session::has('cart') ? Session::get('cart') : null;
        if(!$cart->items){
            return back()->with(['message' => 'musisz dodać coś do koszyka']);
        }
        $deliveries = DB::table('deliveries')->get();
        return view('Cart.checkout', compact('cart', 'deliveries'));
    }
    public function make_order(Request $request){
        $validateData = Validator::make($request->all(),[
            'total_price' => 'required|number',
            'email' => 'required|email',
        ]);
        $cart = Session::has('cart')? Session::get('cart') : null;
        switch ($request->delivery){
            case 'poczta':
                $delivery_price = 10.00;
                break;
            case 'odbior':
                $delivery_price = 0;
                break;
            case 'inpost':
                $delivery_price = 15.00;
                break;
        }
        if(!isset($delivery_price)) $delivery_price = 0;
        $total_price = $cart->totalPrice + $delivery_price;
        $order = Order::create([
            'hash' => md5(str_random(10)),
            'user_id' => null,
            'name' => $request->name,
            'price' => $total_price,
            'street' => $request->street,
            'street_number' => $request->street_number,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'postal_code' => $request->postal_code,
            'comments' => $request->comments,
            'delivery' => $request->shipping,
            'cart' => serialize($cart),
        ]);
        return response()->json($order);

    }
    public function store(Request $request){
        $product = Product::find($request->product_id);
        Mail::to('raccmoon.crat@gmail.com')->send(new OrderMail($request->text, $request->email, $product));
        return view('layouts.thanks_modal')->with(['header' => 'Dziękuję za złożenie zapytania', 'content' => 'Możesz spodziewać się odpowiedzi w ciągu kilku najbliższych dni na adres e-mail: '.$request->email])->render();

    }
}
