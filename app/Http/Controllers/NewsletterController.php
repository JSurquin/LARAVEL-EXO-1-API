<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendNewsletterJob;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
        public function index()
        {
            return view('newsletters.index', [
                'newsletters' => Newsletter::latest()->get(),
            ]);
        }
    
        public function create()
        {
            return view('newsletters.create');
        }
    
        public function store(Request $request)
        {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'body'    => 'required|string',
            ]);
    
            $newsletter = Newsletter::create($validated);
            SendNewsletterJob::dispatch($newsletter, auth()->user());
    
            return redirect()->route('newsletters.index')
                ->with('success', 'Newsletter en file d\'attente — rechargez la liste pour voir « Envoyée ».');
        }
}
