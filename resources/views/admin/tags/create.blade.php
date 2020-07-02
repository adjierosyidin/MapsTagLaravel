    @extends('layouts.admin')
    @section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.tag.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.tags.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.tag.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.tag.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('cruds.tag.fields.description') }}</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.tag.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="img">{{ trans('cruds.tag.fields.img') }}</label>
                    <!-- <input type="file" name="img" id="img-dropzone" class="needsclick dorpzone form-control {{ $errors->has('img') ? 'is-invalid' : '' }}"> -->
                    <div class="needsclick dropzone {{ $errors->has('img') ? 'is-invalid' : '' }}" id="img-dropzone">
                    </div>
                    @if($errors->has('img'))
                        <div class="invalid-feedback">
                            {{ $errors->first('img') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.tag.fields.img_helper') }}</span>
                </div>
                <div class="form-group" >
                    <label for="address">{{ trans('cruds.tag.fields.address') }}</label>
                    <input class="form-control map-input {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address') }}">
                    <div id="manual" style="display:none" class="mt-1">
                    <span style="color:red;font-size:12px"><i>*Jika tidak mengetahui koordinat, click lokasi yang diinginkan di map, lalu masukkan pada kolom secara berurutan latitude dan longitude nya</i></span><br>
                    <label for="latitude">Latitude</label>
                    <input class="form-control" type="text" name="latitude" id="address-latitude" value="{{ old('latitude') ?? '0' }}" />
                    <label for="longitude">Longitude</label>
                    <input class="form-control" type="text" name="longitude" id="address-longitude" value="{{ old('longitude') ?? '0' }}" />
                    </div>
                    @if($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.tag.fields.address_helper') }}</span>
                    <div id="address-map-container" class="mb-2 mt-3" style="width:100%;height:400px; ">
                        <div style="width: 100%; height: 100%" id="address-map"></div>
                    </div>
                    <label for="manual">Tidak Menemukan Lokasi?</label>         
                    <button type="button" class="btn btn-link" onclick="myFunction()">Tambahkan Secara Manual</button>   
                        
                
                </div>
                
                <div class="form-group pt-3">
                    <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 0) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">{{ trans('cruds.tag.fields.active') }}</label>
                    </div>
                    @if($errors->has('active'))
                        <div class="invalid-feedback">
                            {{ $errors->first('active') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.tag.fields.active_helper') }}</span>
                </div>
                
                @foreach ($users as $user)
                <input type="hidden" name="tag_color" id="tag_color" value="{{ $user->tag_color }}">
                @endforeach

                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>


        </div>
    </div>
    @endsection

    @section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize&language=en&region=GB" async defer></script>
    <script src="{{asset('js/mapInput.js')}}"></script>
    <script>
        function myFunction(){
            $('#manual').show(); 
        }


        var uploadedPhotosMap = {}
    Dropzone.options.imgDropzone = {
        url: '{{ route('admin.tags.storeMedia') }}',
        maxFilesize: 2, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        addRemoveLinks: true,
        headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
        size: 2,
        width: 4096,
        height: 4096
        },
        success: function (file, response) {
        $('form').append('<input type="hidden" name="img[]" value="' + response.name + '">')
        uploadedPhotosMap[file.name] = response.name
        },
        removedfile: function (file) {
        console.log(file)
        file.previewElement.remove()
        var name = ''
        if (typeof file.file_name !== 'undefined') {
            name = file.file_name
        } else {
            name = uploadedPhotosMap[file.name]
        }
        $('form').find('input[name="img"][value="' + name + '"]').remove()
        },
        init: function () {
    @if(isset($tag) && $tag->img)
        var files =
            {!! json_encode($tag->img) !!}
            for (var i in files) {
            var file = files[i]
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="img" value="' + file.file_name + '">')
            }
    @endif
        },
        error: function (file, response) {
            if ($.type(response) === 'string') {
                var message = response //dropzone sends it's own error messages in string
            } else {
                var message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            _results = []
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i]
                _results.push(node.textContent = message)
            }

            return _results
        }
    }
    </script>
    @endsection