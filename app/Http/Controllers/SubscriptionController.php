<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    // Create subscription
    public function createSubscription(Request $request){
        // Logic: Only MEMBERS/USERS can create for themselves.
        $this->authorize('create', Subscription::class);

        $validated = $request->validate([
            'user_id'=>'required|int|exists:users,id',
            'bundle_id'=>'required|int|exists:bundles,id',
            'status'=>'required|string|in:ACTIVE,CANCELLED,EXPIRED',
            ]);

        try {
            // Enforcement: Force the user_id to be the authenticated user
            $subscription = Subscription::create([
                'user_id'   => $request->user()->id,
                'bundle_id' => $validated['bundle_id'],
                'status'    => $validated['status'],
            ]);

            return response()->json($subscription->load(['user', 'bundle']), 201);
        }
        catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to save subscription',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Read all subscriptions
     * Logic: Staff/Managers see all. Others are filtered out by the viewAny check
     * except when they are fetching their own subscriptions.
     */
    public function readAllSubscriptions(Request $request){
        $role = $request->user()->role->name;
        try {
            // Start the query with eager loading
            $query = Subscription::with(['user:id,name', 'bundle:id,name']);

            // 1. If Staff/Manager/Admin, they pass 'viewAny' and see all
            if (in_array($role, ['ADMIN', 'GYM_MANAGER', 'STAFF'])) {
                $subscriptions = $query->get();
            }
            // 2. Otherwise, we filter the query to only their own records
            else {
                $subscriptions = $query->where('user_id', $request->user()->id)->get();
            }
            return response()->json($subscriptions);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to fetch subscriptions',
                'message'=> $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Read single subscription
     * Logic: Owner, Gym Manager, or Staff can view.
     */
    public function readSubscription(Subscription $subscription){
        $this->authorize('view', $subscription);
        return response()->json($subscription->load(['user', 'bundle']));
    }

    /**
     * Delete (Cancel) subscription
     * Logic: Only Owner or Gym Manager/Admin can delete.
     */
    public function deleteSubscription(Subscription $subscription){
        $this->authorize('delete', $subscription);

        try {
            $subscription->delete();
            return response()->json(['message' => 'Subscription cancelled successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to delete the subscription',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

}
