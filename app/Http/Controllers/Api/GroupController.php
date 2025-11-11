<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        // শুধু id এবং name ফিল্ডগুলো আনুন
        $groups = Group::select('id', 'name')->get(); 
        return response()->json($groups);
    }

    public function adminIndex()
    {
        return Group::latest()->paginate(10);
    }
    // নতুন গ্রুপ সেভ (Add Modal)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:groups|max:255',
            'description' => 'nullable|string',
        ]);
        $group = Group::create($validated);
        return response()->json($group, 201);
    }

    // গ্রুপ আপডেট (Edit Modal)
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'description' => 'nullable|string',
        ]);
        $group->update($validated);
        return response()->json($group);
    }

    // গ্রুপ ডিলিট
    public function destroy(Group $group)
    {
        // (ঐচ্ছিক: চেক করুন গ্রুপে কোনো সদস্য আছে কিনা)
        $group->delete();
        return response()->json(['message' => 'গ্রুপ ডিলিট করা হয়েছে।'], 200);
    }

    public function getGroupStats(Request $request)
    {
        $groupStats = Group::query()
            // 1. Proti group-e kotogulo registration ache ta count kore
            ->withCount('registrations') 
            
            // 2. Oi registration-gulor 'total_members' column-ti jog kore
            ->withSum('registrations', 'total_members') 
            
            ->orderBy('name')
            ->get();

        /*
         * Output-ti ei rokom hobe:
         * [ 
         * { "id": 1, "name": "Group A", "registrations_count": 5, "registrations_sum_total_members": "20" }, 
         * { "id": 2, "name": "Group B", "registrations_count": 3, "registrations_sum_total_members": "10" } 
         * ]
         */
        
        return response()->json($groupStats);
    }
}