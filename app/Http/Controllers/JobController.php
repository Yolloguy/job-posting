<?php

namespace App\Http\Controllers;

use App\Http\Requests\Jobs\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::paginate(10);
        return response()->json([
            'jobs' => $jobs
        ]);
    }

    public function show($id){
        $job = Job::where('id', $id)->first();

        if(!$job){
            return response()->json([
                'message' => 'job does not exist'
            ], 403);
        }
        return response()->json([
            'job' => $job
        ]);
    }

    public function store(JobRequest $request){
        // Create a new job instance with the validated data from the request
        $job = new Job($request->validated());

        // Set the user_id of the job to the authenticated user's id
        $job->user_id = auth()->id();

        // Save the job to the database
        $job->save();

        return response()->json([
            'job' => $job
        ]);

    }

    public function update(JobRequest $request, $id)
    {
        $job = Job::where('id', $id)->first();

        if(!$job){
            return response()->json([
                'message' => 'job does not exist'
            ], 403);
        }
        // Check if the authenticated user is authorized to update the job
        if (Auth::user()->id != $job->user_id) {
            return response()->json([
                'message' => 'you cannot modify job'
            ], 403);
        }

        // Update the job with the validated data from the request
        $job->update($request->validated());

        return response()->json([
            'message' => 'job updated',
            'job' => $job
        ]);
    }

    public function destroy($id)
    {
        // Check if the authenticated user is authorized to delete the job
        $job = Job::where('id', $id)->first();

        if(!$job){
            return response()->json([
                'message' => 'job does not exist'
            ], 403);
        }

        // Delete the job from the database
        $job->delete();

        // Redirect to the job index page with a success message
        return response()->json([
            'message' => 'job deleted',
        ]);
    }

    public function search(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('q');

        // Search for jobs that match the search term in the title or description
        $jobs = Job::where('title', 'LIKE', "%$searchTerm%")
            ->orWhere('description', 'LIKE', "%$searchTerm%")
            ->paginate(10);

        // Return the search results view with the jobs and search term
        return response()->json([
            'jobs' => $jobs
        ]);
    }

    public function filter(Request $request){
        // Get the filters from the request
        $location = $request->input('location');
        $salaryMin = $request->input('salary_min');
        $salaryMax = $request->input('salary_max');

        // Filter the jobs based on the filters
        $jobs = Job::when($location, function ($query, $location) {
                return $query->where('location', $location);
            })
            ->when($salaryMin, function ($query, $salaryMin) {
                return $query->where('salary','>=', $salaryMin);
            })
            ->when($salaryMax, function ($query, $salaryMax) {
            return $query->where('salary', '<=', $salaryMax);
            })
            ->paginate(10);
        // Return the filtered jobs view with the jobs and filters
        return response()->json([
            'jobs' => $jobs,
            'location' => $location,
            'salaryMin' => $salaryMin,
            'salaryMax' => $salaryMax,
        ]);
    }

}
