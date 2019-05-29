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

    public function store(Request $request){
        $product = Product::find($request->product_id);

        Mail::to('raccmoon.crat@gmail.com')->send(new OrderMail($request->text, $request->email, $product));
        return view('layouts.thanks_modal')->with(['header' => 'Dziękuję za złożenie zapytania', 'content' => 'Możesz spodziewać się odpowiedzi w ciągu kilku najbliższych dni na adres e-mail: '.$request->email])->render();
        $validateData = Validator::make($request->all(),[
           'total_price' => 'required|number',
           'email' => 'required|email',
        ]);
        $delivery = DB::table('deliveries')->find($request->delivery_id);
        $total_price = $delivery->price + $request->price;
        $cart = Session::has('cart')? Session::get('cart') : null;
        $order = Order::create([
            'user_id' => null,
            'name' => $request->name,
            'price' => $request->price,
            'total_price' => $total_price,
            'street' => $request->street,
            'street_number' => $request->street_number,
            'flat_number' => $request->flat_number,
            'city' => $request->city,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'postal_code' => $request->postal_code,
            'payment_id' => null,
            'payment_method' => null,
            'payment_state' => null,
            'payment_create_time' => null,
            'payment_update_time' => null,
            'comments' => $request->comments,
            'delivery_id' => $request->delivery_id,
            'cart' => serialize($cart),
        ]);

        if($request->payment_type == 'paypal'){
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_list = new ItemList();

        foreach ($cart->items as $item){
            $item_1 = new Item();
            $item_1->setName($item->name)
                ->setCurrency('PLN')
                ->setQuantity($item->quanity)
                ->setPrice($item->profit_price);
            $item_list->addItem($item_1);
            if($item->hasProject()){
                $item_2 = new Item();

                $item_2->setName($item->name. ' znakowanie' )
                    ->setCurrency('PLN')
                    ->setQuantity($item->quanity)
                    ->setPrice($item->mark_price);
                $item_list->addItem($item_2);
            }
        }

        if($delivery->price > 0){
            $shipping = new ShippingAddress();
            $shipping->setRecipientName($request->name)
                ->setCity($request->city)
                ->setCountryCode('PL')
                ->setPostalCode($request->postal_code)
                ->setLine1($request->street_number)
                ->setPhone($request->phone_number);
            if(isset($request->flat_number)) $shipping->setLine2($request->flat_number);
            $item_list->setShippingAddress($shipping);
            $amount = new Amount();
            $amount->setCurrency('PLN')
                ->setTotal($total_price)
                ->setDetails([
                    'subtotal' => $request->price,
                    'shipping' => $delivery->price,
                ]);
        } else {
            $amount = new Amount();
            $amount->setCurrency('PLN')
                ->setTotal($total_price)
                ->setDetails([
                    'subtotal' => $request->price,
                ]);
        }


        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Zamówienie Firmowygadżet.pl');


        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('status'))
        ->setCancelUrl(URL::route('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } else {
                die($ex);
                return Redirect::route('status');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        $order->payment_id = $payment->getId();
        $order->save();

        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {

            return Redirect::away($redirect_url);

        }

        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('checkout')->with(['errors' => 'Unknow error occurred']);
        }else{
            $payment_id = md5(rand(2000, 1000000000));
            $order->payment_id = $payment_id;
            $order->save();
            Mail::to($order->email)->send(new OrderMail($order));
            Session::forget('cart');
            return Redirect::route('succes', ['order' => $order->payment_id]);
        }

    }

    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error', 'Payment failed');
            return Redirect::route('home');

        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $order = Order::where('payment_id', $payment_id)->first();
            $order->payment_id = $result->getId();
            $order->payment_create_time = $result->getCreateTime();
            $order->payment_update_time = $result->getUpdateTime();
            $order->payment_state = $result->getState();
            $order->payment_method = $result->getPayer()->getPaymentMethod();
            $order->save();
            Mail::to($order->email)->send(new OrderMail($order));

            Session::forget('cart');
            \Session::put('success', 'Payment success');
            Session::put('payment_id', $order->payment_id);
            return Redirect::route('succes', ['order' => $order->payment_id]);
        } else {
            $order = Order::where('payment_id', $payment_id)->first();
            Mail::to($order->email)->send(new OrderMail($order));
            return Redirect::route('home')->with(['message' => 'płatność się nie powiodła, spróbuj ponownie później']);
        }

        \Session::put('error', 'Payment failed');
        return Redirect::route('checkout');

    }

    public function succes(Request $request, $order){
        $order = Order::where('payment_id', $order)->first();
        $cart = unserialize($order->cart);
/*        Session::forget('payment_id');*/
        return view('Cart.succes', compact('order', 'cart'))->with(['message' => 'twoja płatność została zrealizowana']);
    }
}
