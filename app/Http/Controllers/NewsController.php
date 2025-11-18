<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    //
    /**
     * Display a listing of news.
     */
    public function index()
    {
        $news = News::latest()->with(['author', 'comments','likes'])->paginate(10);
        return new APIResource(true, 'News list retrieved successfully',$news);
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
        }
        $news = News::create([
            'title'   => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'image_path' => $path,
        ]);
        return new APIResource(true, 'News created successfully',$news);
    }

    /**
     * Display the specified news.
     */
    public function show($id)
    {
        $news = News::where('id',$id)->with(['author', 'comments','likes'])->first();

        if (! $news) {
            return new APIResource(false, 'News not found',null);
        }

        return new APIResource(true, 'News retrieved successfully',$news);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request)
    {
        $news = News::find($request->id);

        if (! $news) {
            return new APIResource(false, 'News not found',null);
        }
        
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $news->update([
                'title'   => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
                'image_path' => $path,
            ]);
        } else{
            $news->update([
                'title'   => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);
        }

        return new APIResource(true, 'News updated successfully',$news);
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy($id)
    {
        $news = News::find($id);

        if (! $news) {
            return new APIResource(false, 'News not found',null);
        }

        $news->delete();

        return new APIResource(true, 'News deleted successfully',null);
    }
}
