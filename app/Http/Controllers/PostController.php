<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePost;
use App\Http\Requests\UpdatePost;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = request()->input('filter');
        $perPage = request()->input('perPage') ?? 5;
        $query = Post::query()
            ->select([
                'id',
                'admin_id',
                'title',
                'description',
            ])
            ->when($filter, function (Builder $limit, string $filter) {
                $limit->where(DB::raw('lower(title)'), 'like', '%' . strtolower($filter) . '%');
            })
            ->with('media')
            ->with('admin')
            ->orderBy('id', 'desc');
        $posts = $query->paginate($perPage);
        return $this->paginatedSuccessResponse($posts, 'posts');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePost $request)
    {
        $data = $request->safe(['title', 'description']);
        $post = new Post([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);
        $post->admin()->associate(auth()->guard('admin')->id());
        $post->save();
        $this->storePostMedia($request->file('file'), $post->id, auth()->guard('admin')->id());
        if ($post) {
            return $this->successResponse([
                'message' => 'Post Created',
            ]);
        }
        return $this->failResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return base64_encode(Storage::get($media->url));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePost $request, Post $post)
    {
        $data = $request->safe(['title', 'description']);
        if ($data['title']) {
            $post->title = $data['title'];
        }
        if ($data['description']) {
            $post->description = $data['description'];
        }
        if ($request->file('file')) {
            $this->storePostMedia($request->file('file'), $post->id, auth()->guard('admin')->id());
        }
        if ($post->update()) {
            return $this->successResponse([
                'message' => 'Post Updated',
            ]);
        }
        return $this->failResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (auth()->guard('admin')) {
            if ($post->media)
                $this->deleteMedia($post->media);
            if ($post->delete()) {
                return $this->successResponse([
                    'message' => 'Post Deleted',
                ]);
            }
            return $this->failResponse();
        }
    }
}
