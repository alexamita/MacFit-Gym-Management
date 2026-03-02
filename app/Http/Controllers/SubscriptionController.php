<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    // Create subscription
    public function createSubscription(Request $request){

       //Allow only the current user to create their own subscriptions
    $this->authorize('create', Subscription::class);

        $user = $request->user();

        $validated = $request->validate([
            'bundle_id'=>'required|int|exists:bundles,id',
            ]);

        try {
            $bundle = Bundle::query()->findOrFail($validated['bundle_id']);

        /**
         * Enforce bundle availability by gym:
         * - Global bundle: gym_id is NULL => allowed for anyone
         * - Gym bundle: gym_id must equal user's gym_id
         */

        $userGymId = $user->gym_id ?? null;

        $bundleAllowed =
            is_null($bundle->gym_id) ||
            (!is_null($userGymId) && (int) $bundle->gym_id === (int) $userGymId);

        if (!$bundleAllowed) {
            return response()->json([
                'error' => 'This bundle is not available for your gym.',
            ], 403);
        }

         // Subscription period
        $startsAt  = now();
        $expiresAt = $startsAt->copy()->addMonth(); // one month from date of subscription

        // Dynamic status using constants in the model
        $status = $expiresAt->isPast()
            ? Subscription::STATUS_EXPIRED
            : Subscription::STATUS_ACTIVE;

        // Block duplicate ACTIVE subscriptions for same user + bundle
        $alreadyActive = Subscription::query()
            ->where('user_id', $user->id)
            ->where('bundle_id', $bundle->id)
            ->active() // uses your scopeActive()
            ->exists();

        if ($alreadyActive) {
            return response()->json([
                'error' => 'You already have an active subscription for this bundle.',
            ], 409);
        }

        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'bundle_id'  => $bundle->id,
            'status'     => $status,
            'starts_at'  => $startsAt,
            'expires_at' => $expiresAt,
        ]);

        return response()->json($subscription->load(['user_id', 'bundle']), 201);

        }
        catch (\Exception $exception) {
            return response()->json([
            'error'   => 'Failed to save subscription',
            'message' => $exception->getMessage(),
        ], 500);
        }
    }

    /**
     * Read all subscriptions (ONLY current user)
     * Logic: Staff/Managers see all.
     */
    public function readAllSubscriptions(Request $request){

        try {
            $user = $request->user();
            $roleSlug = $user->role?->slug;

            $isPrivileged = in_array($roleSlug, ['admin', 'gym_manager', 'staff'], true);

            $query = Subscription::query()
                ->with(['user:id,name', 'bundle:id,name'])
                ->latest();

            if ($isPrivileged) {
                // privileged master list access
                $this->authorize('viewAny', Subscription::class);
            } else {
                // normal users: only their own subscriptions
                $query->where('user_id', $user->id);
            }

            return response()->json($query->get());

        }
        catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Failed to fetch subscriptions',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Read single subscription
     * Logic: Owner, Gym Manager, or Staff can view.
     */
    public function readSubscription(Request $request, int $id){

        $subscription = Subscription::with(['user', 'bundle'])
        ->findOrFail($id);

        // Policy handles:
        // - owner can view
        // - admin/gym_manager/staff can view
        $this->authorize('view', $subscription);

        return response()->json($subscription);
    }

    /**
     * Delete (Cancel) subscription
     * Logic: Only Owner or Gym Manager/Admin can delete.
     */
    public function deleteSubscription(Subscription $subscription){
        $this->authorize('delete', $subscription);

        try {
            $subscription->cancel(); //set status = cancelled
            return response()->json(['message' => 'Subscription cancelled successfully'], 200);
        }
        catch (\Throwable $exception) {
            return response()->json([
                'error'   => 'Failed to cancel the subscription',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function getTotalUserCharge(Request $request){
        $user = $request->user();

        $total = Subscription::query()
            ->where('subscriptions.user_id', $user->id)
            ->where('subscriptions.status', Subscription::STATUS_ACTIVE)
            ->join('bundles', 'subscriptions.bundle_id', '=', 'bundles.id')
            ->sum('bundles.price');

        return response()->json([
            'user_id'  => $user->id,
            'total'    => (float) $total,
            'currency' => 'KES',
        ]);
    }


}
