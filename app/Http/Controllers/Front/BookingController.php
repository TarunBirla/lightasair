<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Item;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function addToCart(Request $request)
    {
        $item = Item::findOrFail(
            $request->item_id
        );

        $cart = session()->get(
            'cart',
            []
        );

        if (isset($cart[$item->id])) {
            $cart[$item->id]['qty'] +=
                $request->qty;
        } else {
            $cart[$item->id] = [

                'item_id' => $item->id,

                'title' => $item->title,

                'image' => $item->image,

                'qty' => $request->qty,

                'price' => $item->price_per_day

            ];
        }

        session()->put(
            'cart',
            $cart
        );

        return redirect('/cart')
            ->with(
                'success',
                'Item Added Successfully'
            );
    }
    public function increaseQty($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        }

        session()->put('cart', $cart);

        return back();
    }

    public function decreaseQty($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty']--;
            } else {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return back();
    }

    public function cart()
    {
        return view(
            'front.cart'
        );
    }

    public function checkout(Request $request)
    {
        $cart = session('cart');

        $days = \Carbon\Carbon::parse(
            $request->start_date
        )->diffInDays(
                \Carbon\Carbon::parse(
                    $request->end_date
                )
            ) + 1;

        $total = 0;

        foreach ($cart as $row) {
            $total +=
                (
                    $row['price']
                    *
                    $row['qty']
                    *
                    $days
                );
        }

        $booking = Booking::create([

            'booking_no' =>
                'BK' . time(),

            'user_id' =>
                auth()->id(),

            'total_amount' =>
                $total,

            'start_date' =>
                $request->start_date,

            'end_date' =>
                $request->end_date,

            'total_days' =>
                $days,

            'status' =>
                'pending'
        ]);

        foreach ($cart as $row) {
            BookingItem::create([

                'booking_id' =>
                    $booking->id,

                'item_id' =>
                    $row['item_id'],

                'qty' =>
                    $row['qty'],

                'price_per_day' =>
                    $row['price'],

                'total_days' =>
                    $days,

                'total_amount' =>
                    (
                        $row['price']
                        *
                        $row['qty']
                        *
                        $days
                    )
            ]);
        }

        session()->forget('cart');

        return redirect(
            '/my-bookings'
        );
    }
    public function myBookings()
    {
        $bookings = Booking::where(
            'user_id',
            auth()->id()
        )
            ->latest()
            ->get();

        return view(
            'front.my-bookings',
            compact('bookings')
        );
    }
    public function checkoutPage()
    {
        return view('front.checkout');
    }
    public function bookingDetail($id)
    {
        $booking = Booking::with(
            'items.item'
        )
            ->where(
                'user_id',
                auth()->id()
            )
            ->findOrFail($id);

        return view(
            'front.booking-detail',
            compact('booking')
        );
    }
    // public function placeOrder(Request $request)
    // {
    //     $cart = session('cart', []);

    //     if (empty($cart)) {
    //         return redirect('/cart');
    //     }

    //     $days =
    //         \Carbon\Carbon::parse(
    //             $request->start_date
    //         )->diffInDays(
    //                 \Carbon\Carbon::parse(
    //                     $request->end_date
    //                 )
    //             ) + 1;

    //     $total = 0;

    //     foreach ($cart as $row) {
    //         $total +=
    //             (
    //                 $row['price']
    //                 *
    //                 $row['qty']
    //                 *
    //                 $days
    //             );
    //     }

    //     $booking = Booking::create([

    //         'booking_no' =>
    //             'BK' . rand(10000, 99999),

    //         'user_id' =>
    //             auth()->id(),

    //         'total_amount' =>
    //             $total,

    //         'start_date' =>
    //             $request->start_date,

    //         'end_date' =>
    //             $request->end_date,

    //         'total_days' =>
    //             $days,

    //         'status' =>
    //             'pending'

    //     ]);

    //     foreach ($cart as $row) {
    //         BookingItem::create([

    //             'booking_id' =>
    //                 $booking->id,

    //             'item_id' =>
    //                 $row['item_id'],

    //             'qty' =>
    //                 $row['qty'],

    //             'price_per_day' =>
    //                 $row['price'],

    //             'total_days' =>
    //                 $days,

    //             'total_amount' =>
    //                 (
    //                     $row['price']
    //                     *
    //                     $row['qty']
    //                     *
    //                     $days
    //                 )
    //         ]);
    //     }

    //     session()->forget('cart');

    //     return redirect('/my-bookings')
    //         ->with(
    //             'success',
    //             'Booking Created Successfully'
    //         );
    // }



   public function placeOrder(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date'
    ]);

    $cart = session('cart', []);

    if (empty($cart)) {
        return response()->json([
            'status' => false,
            'message' => 'Cart Empty'
        ]);
    }

    $days = \Carbon\Carbon::parse($request->start_date)
        ->diffInDays(
            \Carbon\Carbon::parse($request->end_date)
        ) + 1;

    $total = 0;

    foreach ($cart as $row) {
        $total += (
            $row['price']
            * $row['qty']
            * $days
        );
    }

    $booking = Booking::create([
        'booking_no'   => 'BK' . rand(10000, 99999),
        'user_id'      => auth()->id(),
        'total_amount' => $total,
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
        'total_days'   => $days,
        'status'       => 'pending'
    ]);

    $itemsData = [];

    foreach ($cart as $row) {

        BookingItem::create([
            'booking_id'    => $booking->id,
            'item_id'       => $row['item_id'],
            'qty'           => $row['qty'],
            'price_per_day' => $row['price'],
            'total_days'    => $days,
            'total_amount'  => (
                $row['price']
                * $row['qty']
                * $days
            )
        ]);

        $itemsData[] = [
            'title' => $row['title'],
            'qty'   => $row['qty'],
            'price' => $row['price']
        ];
    }

    session()->forget('cart');

    return response()->json([
        'status'     => true,
        'booking_no' => $booking->booking_no,
        'total'      => $booking->total_amount,
        'items'      => $itemsData
    ]);
}

    public function removeCart($id)
    {
        $cart = session()->get(
            'cart',
            []
        );

        if (isset($cart[$id])) {
            unset($cart[$id]);

            session()->put(
                'cart',
                $cart
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Item Removed Successfully'
            );
    }
}