<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use App\Models\Bookable;
use App\Models\Booking;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'bookings'                  => 'required|array|min:1',
            'bookings.*.bookableid'     => 'required|exists:bookables,id',
            'bookings.*.from'           => 'required|date|after_or_equal:today',
            'bookings.*.to'             => 'required|date|after_or_equal:bookings.*.from',
            'customer.first_name'       => 'required|min:2',
            'customer.last_name'        => 'required|min:2',
            'customer.email'            => 'required|email',
            'customer.address'          => 'required|min:2',
            'customer.country'          => 'required|min:2',
            'customer.state'            => 'required|min:2',
            'customer.zip'              => 'required|min:2'
        ]);

        $data = array_merge($data, $request->validate([
            'bookings.*'    => ['required', function($attribute, $value, $fail) {
                $bookable = Bookable::findOrFail($value['bookableid']);

                if ( !$bookable->isAvailable($value['from'], $value['to']) ) {
                    $fail("The object is not available in given dates");
                }
            }]
        ]));

        $bookingsData   = $data['bookings'];
        $addressData    = $data['customer'];

        $bookings = collect($bookingsData)->map(function ($bookingData) use ($addressData) {

            $bookable = Bookable::findOrFail($bookingData['bookableid']);
            $booking = new Booking;
            $booking->from = $bookingData['from'];
            $booking->to   = $bookingData['to'];
            $booking->price = $bookable->priceFor($bookingData['from'], $bookingData['to'])['total'];
            // $booking->bookable_id = $bookingData['bookableid'];
            $booking->bookable()->associate($bookable);

            $booking->address()->associate(Addresses::create($addressData));

            $booking->save();
            return $booking;
        });

        return $bookings;
    }
}
