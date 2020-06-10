<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\Admin\TagResource;
use App\Tag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagResource(Tag::with(['created_by'])->get());
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        if ($request->input('img', false)) {
            $tag->addMedia(storage_path('tmp/uploads/' . $request->input('img')))->toMediaCollection('img');
        }

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tag $tag)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TagResource($tag->load(['created_by']));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->all());

        if ($request->input('img', false)) {
            if (!$tag->img || $request->input('img') !== $tag->img->file_name) {
                $tag->addMedia(storage_path('tmp/uploads/' . $request->input('img')))->toMediaCollection('img');
            }
        } elseif ($tag->img) {
            $tag->img->delete();
        }

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tag $tag)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
