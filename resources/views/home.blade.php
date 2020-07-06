    @extends('layouts.main')

    @section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-box content-single">
                    <article class="post-8 page type-page status-publish hentry">
                        <header>
                            <h1 class="entry-title">{{ request()->filled('search') || request()->filled('date') ? 'Search results' : 'All Tags' }}</h1></header>
                        <div class="entry-content entry-summary">
                            <div class="geodir-search-container geodir-advance-search-default" data-show-adv="default">
                                <form class="geodir-listing-search gd-search-bar-style" name="geodir-listing-search" action="{{ route('home') }}" method="get">
                                    <div class="geodir-loc-bar">
                                        <div class="clearfix geodir-loc-bar-in">
                                            <div class="geodir-search">
                                                <div class='gd-search-input-wrapper gd-search-field-cpt gd-search-field-taxonomy gd-search-field-categories'>
                                                    <input  type="date" class="date" value="{{ old('date', request()->input('date')) }}" name="date"/>
                                                </div>
                                                <div class='gd-search-input-wrapper gd-search-field-search'> <span class="geodir-search-input-label"><i class="fas fa-search gd-show"></i><i class="fas fa-times geodir-search-input-label-clear gd-hide" title="Clear field"></i></span>
                                                    <input class="search_text gd_search_text" name="search" value="{{ old('search', request()->input('search')) }}" type="text" placeholder="Search for" aria-label="Search for" autocomplete="off" />
                                                </div>
                                                <button class="geodir_submit_search" data-title="fas fa-search" aria-label="fas fa-search"><i class="fas fas fa-search" aria-hidden="true"></i><span class="sr-only">Search</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="geodir-loop-container">
                                <ul class="geodir-category-list-view clearfix gridview_onethird geodir-listing-posts geodir-gridview gridview_onethird">
                                    @foreach($tags as $tag)
                                        <li class="gd_place type-gd_place status-publish has-post-thumbnail">
                                            <div class="gd-list-item-left ">
                                                <div class="geodir-post-slider">
                                                    <div class="geodir-image-container geodir-image-sizes-medium_large">
                                                        <div class="geodir-image-wrapper">
                                                            <ul class="geodir-post-image geodir-images clearfix">
                                                                <li>
                                                                    <a href='{{ route('tag', $tag->id) }}'>
                                                                        <img src="{{ $tag->thumbnail }}" width="1440" height="960" class="geodir-lazy-load align size-medium_large" />
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gd-list-item-right ">
                                                <div class="geodir-post-title">
                                                    <h2 class="geodir-entry-title"> <a href="{{ route('tag', $tag->id) }}" title="View: {{ $tag->name }}">{{ $tag->name }}</a></h2>
                                                </div>
                                                <div class="geodir-post-content-container">
                                                    <div class="geodir_post_meta  geodir-field-post_content" style='max-height:120px;'>{{ $tag->address }} </div>
                                                </div>
                                                <div class="geodir-post-content-container">
                                                    <div class="geodir_post_meta  geodir-field-post_content" style='max-height:120px;overflow:hidden;'>{{ $tag->description }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="clear"></div>
                            </div>
                            
                        </div>
                        <footer class="entry-footer"></footer>
                    </article>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script type='text/javascript' src='https://maps.google.com/maps/api/js?language=en&key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&region=GB'></script>
    <script defer>
        function initialize() {
            var mapOptions = {
                zoom: 15,
                minZoom: 6,
                maxZoom: 17,
                zoomControl:true,
                zoomControlOptions: {
                    style:google.maps.ZoomControlStyle.DEFAULT
                },
                center: new google.maps.LatLng(-7.939794, 112.621231),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false,
                panControl:false,
                mapTypeControl:false,
                scaleControl:false,
                overviewMapControl:false,
                rotateControl:false
            }

            
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            
            var places = @json($tags);

            for(place in places)
            {   
                place = places[place];
                var pinColor = place.tag_color;
                var image = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor, null, null, null, new google.maps.Size(34,49));
                if(place.latitude && place.longitude)
                {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(place.latitude, place.longitude),
                        icon:image,
                        map: map,
                        title: place.name
                    });
                    var infowindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(marker, 'click', (function (marker, place) {
                        return function () {
                            infowindow.setContent(generateContent(place))
                            infowindow.open(map, marker);
                        }
                    })(marker, place));
                }
            }
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        function generateContent(place){
        var content = `
            <div class="gd-bubble" style="">
                <div class="gd-bubble-inside">
                    <div class="geodir-bubble_desc">
                    <div class="geodir-bubble_image">
                        <div class="geodir-post-slider">
                            <div class="geodir-image-container geodir-image-sizes-medium_large ">
                                <div id="geodir_images_5de53f2a45254_189" class="geodir-image-wrapper" data-controlnav="1">
                                    <ul class="geodir-post-image geodir-images clearfix">
                                        <li>
                                            <div class="geodir-post-title">
                                                <h4 class="geodir-entry-title">
                                                    <a href="{{ route('tag', '') }}/`+place.id+`" title="View: `+place.name+`">`+place.name+`</a>
                                                </h4>
                                            </div>
                                            <a href="{{ route('tag', '') }}/`+place.id+`"><img src="`+place.thumbnail+`" alt="`+place.name+`" class="align size-medium_large" width="1400" height="930"></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="geodir-bubble-meta-side">
                    <div class="geodir-output-location">
                    <div class="geodir-output-location geodir-output-location-mapbubble">
                        <div class="geodir_post_meta  geodir-field-post_title"><span class="geodir_post_meta_icon geodir-i-text">
                            <i class="fas fa-minus" aria-hidden="true"></i>
                            <span class="geodir_post_meta_title">Place Title: </span></span>`+place.name+`</div>
                        <div class="geodir_post_meta  geodir-field-address" itemscope="" itemtype="http://schema.org/PostalAddress">
                            <span class="geodir_post_meta_icon geodir-i-address"><i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span class="geodir_post_meta_title">Address: </span></span><span itemprop="streetAddress">`+place.address+`</span>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            </div>
            </div>`;

    return content;

    }
    </script>
    @endsection