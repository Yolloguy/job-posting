<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all jobs from the database
        $jobs = Job::all();

        // Return the jobs view with the jobs
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        // Return the create job view
        return view('jobs.create');
    }

    public function store(JobRequest $request)
    {
        // Create a new job instance with the validated data from the request
        $job = new Job($request->validated());

        // Set the user_id of the job to the authenticated user's id
        $job->user_id = Auth::user()->id;

        // Save the job to the database
        $job->save();

        // Redirect to the job index page with a success message
        return Redirect::route('jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        // Check if the authenticated user is authorized to edit the job
        if (Auth::user()->id != $job->user_id) {
            abort(403);
        }

        // Return the edit job view with the job
        return view('jobs.edit', compact('job'));
    }

    public function update(JobRequest $request, Job $job)
    {
        // Check if the authenticated user is authorized to update the job
        if (Auth::user()->id != $job->user_id) {
            abort(403);
        }

        // Update the job with the validated data from the request
        $job->update($request->validated());

        // Redirect to the job index page with a success message
        return Redirect::route('jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        // Check if the authenticated user is authorized to delete the job
        if (Auth::user()->id != $job->user_id) {
            abort(403);
        }

        // Delete the job from the database
        $job->delete();

        // Redirect to the job index page with a success message
        return Redirect::route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function search(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('q');

        // Search for jobs that match the search term in the title or description
        $jobs = Job::where('title', 'LIKE', "%$searchTerm%")
            ->orWhere('description', 'LIKE', "%$searchTerm%")
            ->get();

        // Return the search results view with the jobs and search term
        return view('jobs.search', compact('jobs', 'searchTerm'));
    }

    public function filter(Request $request)
    {
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
            ->get();
               // Return the filtered jobs view with the jobs and filters
    return view('jobs.filter', compact('jobs', 'location', 'salaryMin', 'salaryMax'));
}

}
