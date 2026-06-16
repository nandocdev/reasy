<?php

declare(strict_types=1);

namespace App\Modules\Tenant\Reasy\Bookings\Http\Controllers;

use App\Modules\Tenant\Reasy\Bookings\Actions\ReserveSlotAction;
use App\Modules\Tenant\Reasy\Bookings\Actions\ConfirmBookingAction;
use App\Modules\Tenant\Reasy\Bookings\Actions\CancelBookingAction;
use App\Modules\Tenant\Reasy\Bookings\Actions\CompleteBookingAction;
use App\Modules\Tenant\Reasy\Bookings\Actions\MarkNoShowAction;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ReserveSlotData;
use App\Modules\Tenant\Reasy\Bookings\DTOs\ConfirmBookingData;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CancelBookingData;
use App\Modules\Tenant\Reasy\Bookings\DTOs\CompleteBookingData;
use App\Modules\Tenant\Reasy\Bookings\DTOs\MarkNoShowData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookingController extends Controller
{
    public function reserve(Request $request, ReserveSlotAction $action)
    {
        $data = ReserveSlotData::validate($request->all());
        $booking = $action->execute($data);
        return response()->json($booking, 201);
    }

    public function confirm(string $id, ConfirmBookingAction $action)
    {
        $data = new ConfirmBookingData(booking_id: $id);
        $booking = $action->execute($data);
        return response()->json($booking);
    }

    public function cancel(string $id, Request $request, CancelBookingAction $action)
    {
        $data = new CancelBookingData(
            booking_id: $id,
            reason: $request->input('reason', 'No reason provided')
        );
        $booking = $action->execute($data);
        return response()->json($booking);
    }

    public function complete(string $id, Request $request, CompleteBookingAction $action)
    {
        $data = new CompleteBookingData(
            booking_id: $id,
            notes: $request->input('notes')
        );
        $booking = $action->execute($data);
        return response()->json($booking);
    }

    public function noShow(string $id, Request $request, MarkNoShowAction $action)
    {
        $data = new MarkNoShowData(
            booking_id: $id,
            reason: $request->input('reason')
        );
        $booking = $action->execute($data);
        return response()->json($booking);
    }
}
