<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;
use App\Tag;
use App\User;
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
        $users = User::where('id', '=', auth()->id())->get();

        return view('admin.tags.create', compact('users'));
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        foreach ($request->input('img', []) as $file) {
            $tag->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('img');
        }

        return redirect()->route('admin.tags.index');

    }

    public function edit(Tag $tag)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $tag->load('created_by');

        return view('admin.tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        if(!$request->active){
            $request->merge([
                'active' => 0
            ]);
        }
        $tag->update($request->all());

        if (count($tag->img) > 0) {
            foreach ($tag->img as $media) {
                if (!in_array($media->file_name, $request->input('img', []))) {
                    $media->delete();
                }
            }
        }

        $media = $tag->img->pluck('file_name')->toArray(); 

        foreach ($request->input('img', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $tag->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('img');
            }
        }

        return redirect()->route('admin.tags.index');
    }

    public function show(Tag $tag)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tags = Tag::all();
        $tag->load('created_by');

        return view('admin.tags.show', compact('tag'));
    }

    public function destroy(Tag $tag)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();

        return back();
    }

    /* public function update(UpdateTagRequest $request, Tag $tag)
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
    } */

    public function massDestroy(MassDestroyTagRequest $request)
    {
        Tag::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
