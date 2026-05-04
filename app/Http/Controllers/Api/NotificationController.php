<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(StoreNotificationRequest $request)
    {
        $notification = $this->notificationService->createNotification(
            $request->user_id,
            $request->channel,
            $request->message
        );

        SendNotificationJob::dispatch($notification);

        return new NotificationResource($notification);
    }

    public function show(Notification $notification)
    {
        return new NotificationResource($notification);
    }

    public function userHistory(Request $request, int $userId)
    {
        $query = Notification::query()->where('user_id', $userId);

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('channel')) {
            $query->where('channel', $request->input('channel'));
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }
}
