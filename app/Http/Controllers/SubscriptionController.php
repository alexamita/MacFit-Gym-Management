<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    // Create subscription
    public function createSubscription(Request $request){
        $validated = $request->validate([
            'user_id'=>'required|int|exists:users,id',
            'bundle_id'=>'required|int|exists:bundles,id',
            'status'=>'required|string|in:ACTIVE,CANCELLED,EXPIRED',
            ]);
            $subscription = new Subscription();
            $subscription->user_id = $validated['user_id'];
            $subscription->bundle_id = $validated['bundle_id'];
            $subscription->status = $validated['status'];

            try{
                $createdSubscription = $subscription->save();
                return response()->json($subscription);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save subscription',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all subscriptions
    public function readAllSubscriptions(){
        try{
            // $subscriptions = Subscription::all();

            // join subscriptions with user and bundle names
            $subscriptions = Subscription::join('users', 'subscriptions.user_id', '=', 'users.id')
            ->join('bundles', 'subscriptions.bundle_id', '=', 'bundles.id')
            ->select('subscriptions.*', 'users.name as user_name', 'bundles.name as bundle_name')
            ->get();
            return response()->json($subscriptions);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch subscriptions',
                    'message'=> $exception->getMessage()
                ]);
            }
    }


    // Read single subscription
    public function readSubscription($id){
        try {
            $subscription = Subscription::findOrFail($id);
            return response()->json($subscription);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the subscription with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Update subscription
    public function updateSubscription(Request $request, $id){
        $validated = $request->validate([
            'user_id'=>'required|int|exists:users,id',
            'bundle_id'=>'required|int|exists:bundles,id',
            'status'=>'required|string|in:ACTIVE,CANCELLED,EXPIRED',
            ]);

        try{
            $subscription = Subscription::findOrFail($id);
            $subscription->user_id = $validated['user_id'];
            $subscription->bundle_id = $validated['bundle_id'];
            $subscription->status = $validated['status'];
            $subscription->save();
            return response()->json($subscription);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the subscription with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Delete subscription
    public function deleteSubscription($id){
        try{
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();
            return response("Subscription deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the subscription',
                'message'=> $exception->getMessage()
            ]);
        }
    }

}
