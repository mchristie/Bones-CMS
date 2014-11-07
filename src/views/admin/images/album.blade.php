
@if(count($images) == 0)
    <div class="alert alert-info">There are no images in this album</div>
@endif

@foreach($images as $image)

    <div class="panel panel-default col-md-3 image"
         data-image-id="{{$image->id}}"
         onclick="preview('{{$image->filename}} ({{$image->id}})', '{{$image->url(560)}}');">

        <img src="{{$image->url(150, 150)}}" style="max-width: 100%;" />

    </div>

@endforeach
