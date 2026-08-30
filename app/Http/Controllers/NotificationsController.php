<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Resources\NotificationResource;
use Laravel\Nova\Notifications\Notification;

class NotificationsController extends Controller
{
    /**
     * Full notifications page inside Nova.
     */
    public function index(NovaRequest $request): Response
    {
        $user = $request->user();

        $paginator = Notification::query()
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey())
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $data = collect($paginator->items())
            ->map(fn (Notification $notification) => (new NotificationResource($notification))->resolve($request))
            ->values()
            ->all();

        return inertia('Notifications', [
            'notifications' => [
                'data' => $data,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ],
            ],
            'unreadCount' => Notification::query()
                ->unread()
                ->where('notifiable_type', $user->getMorphClass())
                ->where('notifiable_id', $user->getKey())
                ->count(),
        ]);
    }

    public function markRead(Request $request, string $notification): JsonResponse
    {
        $this->findOwned($request, $notification)->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markUnread(Request $request, string $notification): JsonResponse
    {
        $this->findOwned($request, $notification)->update(['read_at' => null]);

        return response()->json(['success' => true]);
    }

    public function destroy(Request $request, string $notification): JsonResponse
    {
        $this->findOwned($request, $notification)->delete();

        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        Notification::query()
            ->where('notifiable_type', $request->user()->getMorphClass())
            ->where('notifiable_id', $request->user()->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroyAll(Request $request): JsonResponse
    {
        Notification::query()
            ->where('notifiable_type', $request->user()->getMorphClass())
            ->where('notifiable_id', $request->user()->getKey())
            ->delete();

        return response()->json(['success' => true]);
    }

    protected function findOwned(Request $request, string $id): Notification
    {
        return Notification::query()
            ->where('notifiable_type', $request->user()->getMorphClass())
            ->where('notifiable_id', $request->user()->getKey())
            ->whereKey($id)
            ->firstOrFail();
    }
}
