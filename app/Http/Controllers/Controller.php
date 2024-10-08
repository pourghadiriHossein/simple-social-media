<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
abstract class Controller
{
    function paginatedSuccessResponse($data, $name)
    {
        return response()->json(
            [
                'status' => 'success',
                $name => $data->items(),
                'meta' => [
                    'pagination' => [
                        $name => [
                            'last_item' => $data->lastItem(),
                            'first_item' => $data->firstItem(),
                            'last_page' => $data->lastPage(),
                            'per_page' => $data->perPage(),
                            'current_page' => $data->currentPage(),
                            'total' => $data->total()
                        ]
                    ]
                ]
            ]
        );
    }

    function successResponse($data = [], $code = 200)
    {
        return response()->json(
            array_merge([
                'message' => 'Your Request Succeeded',
            ], $data),
            $code
        );
    }

    function failResponse($data = [], $code = 504)
    {
        return response()->json(
            array_merge([
                'message' => 'Your Request Failed',
            ], $data),
            $code
        );
    }

    function deleteMedia($media)
    {
        if ($media) {
            foreach ($media as $file) {
                Storage::delete($file->url);
                $file->delete();
            }
        }
        return back();
    }

    function storePostMedia($file, $model_id, $admin_id)
    {
        $post = Post::where('id', $model_id)->with('media')->first();
        if (isset($post->media)) {
            $this->deleteMedia($post->media);
        }
        $name = Carbon::now()->getTimestamp() . '.' . $file->extension();
        $file->storePubliclyAs('public/media/', $name);
        $media = new Media([
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'url' => 'public/media/' . $name
        ]);
        $media->admin()->associate($admin_id);
        $media->save();

        $post = Post::find($model_id);
        $post->media()->sync($media, ['create_at' => Carbon::now()]);
        $post->save();
        return back();
    }
}
