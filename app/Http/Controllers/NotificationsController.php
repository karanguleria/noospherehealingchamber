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

        $filter = $request->string('filter', 'all')->toString();
        if (! in_array($filter, ['all', 'unread', 'read'], true)) {
            $filter = 'all';
        }

        $perPage = (int) $request->input('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50], true) ? $perPage : 15;

        $baseQuery = Notification::query()
            ->where('notifiable_type', $user->getMorphClass())
            ->where('notifiable_id', $user->getKey());

        $totalCount = (clone $baseQuery)->count();
        $unreadCount = (clone $baseQuery)->whereNull('read_at')->count();
        $readCount = max(0, $totalCount - $unreadCount);

        $query = (clone $baseQuery)->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $paginator = $query
            ->paginate($perPage)
            ->appends($request->only(['filter', 'per_page']))
            ->withQueryString();

        $data = collect($paginator->items())
            ->map(fn (Notification $notification) => $this->normalizeNotification(
                (new NotificationResource($notification))->resolve($request)
            ))
            ->values()
            ->all();

        return inertia('Notifications', [
            'notifications' => [
                'data' => $data,
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'prev_page_url' => $paginator->previousPageUrl(),
                'next_page_url' => $paginator->nextPageUrl(),
            ],
            'filter' => $filter,
            'perPage' => $perPage,
            'unreadCount' => $unreadCount,
            'readCount' => $readCount,
            'totalCount' => $totalCount,
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

    /**
     * @param  array<string, mixed>  $notification
     * @return array<string, mixed>
     */
    protected function normalizeNotification(array $notification): array
    {
        $actionUrl = $notification['actionUrl'] ?? null;

        if (is_array($actionUrl)) {
            $notification['actionUrl'] = $actionUrl['url'] ?? null;
            $notification['openInNewTab'] = (bool) ($actionUrl['remote'] ?? $notification['openInNewTab'] ?? false);
        } elseif (is_object($actionUrl) && isset($actionUrl->url)) {
            $notification['actionUrl'] = $actionUrl->url;
            $notification['openInNewTab'] = (bool) ($actionUrl->remote ?? false);
        }

        if (isset($notification['read_at']) && is_object($notification['read_at'])) {
            $notification['read_at'] = method_exists($notification['read_at'], 'toIso8601String')
                ? $notification['read_at']->toIso8601String()
                : (string) $notification['read_at'];
        }

        // Nova.visit expects paths relative to /nova (e.g. /resources/users/1)
        if (is_string($notification['actionUrl'] ?? null)) {
            $url = $notification['actionUrl'];
            if (str_starts_with($url, '/nova/')) {
                $notification['actionUrl'] = substr($url, 5) ?: '/';
            }
        }

        return $notification;
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
