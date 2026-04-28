<?php

namespace App\Http\Controllers\Cabinet;

use App\Console\Commands\Sinc\DevisesSincCommand;
use App\Console\Commands\Sinc\DevisesUnSincCommand;
use App\Http\Controllers\Controller;
use App\Jobs\DeviseCreateJob;
use App\Jobs\DeviseDeleteJob;
use App\Jobs\DeviseSingJob;
use App\Models\Devise;
use App\Models\SincJobs;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required'
        ]);

        $devise = Devise::query()->create([
            'name'=>$request->name,
            'user_id'=>auth()->id(),
        ]);
        DeviseCreateJob::dispatch($devise)->onQueue('high');

        return back();
    }

    public function destroy(Devise $devise)
    {
        DeviseDeleteJob::dispatch($devise);

        $devise->update(['del'=>1]);

        return back();
    }
}
