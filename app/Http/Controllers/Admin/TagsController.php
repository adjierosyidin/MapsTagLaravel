<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use App\Tag;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class TagsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {

        abort_if(Gate::denies('tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = Tag::all();

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        abort_if(Gate::denies('tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        foreach ($request->input('photos', []) as $file) {
            $tag->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('photos');
        }

        return redirect()->route('admin.tags.index');
    }

    public function edit(Tag $tag)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $tag->load('created_by');

        return view('admin.tags.edit', compact('tag'));
    }

    public function show(Tag $tag)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = Tag::all();

        return view('admin.tags.show', compact('tag'));
    }

    public function destroy(Tag $tag)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();

        return back();
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        if($request['img']!="undefined"){
            
            $image       = $request['img'];
            $upload_path = 'assets/images/tags';
            $file_name   = time()."_".$image->getClientOriginalName();
            $image->move($upload_path, $file_name);

            return parent::update($id,[
                'id'            => e($request['id']),
                'nama'          => e($request['nama']),
                'address'       => e($request['address']),
                'latitude'      => e($request['latitude']),
                'longitude'     => e($request['longitude']),
                'description'   => e($request['description']),
                'img'           => e($upload_path.$file_name),
                'active'        => e($request['active'])
            ]);
        }else{
            return parent::update($id, [
                'id'            => e($request['id']),
                'nama'          => e($request['nama']),
                'address'       => e($request['address']),
                'latitude'      => e($request['latitude']),
                'longitude'     => e($request['longitude']),
                'description'   => e($request['description']),
                'active'        => e($request['active'])
            ]);
        }
        

        return redirect()->route('admin.tags.index');
    }
}
