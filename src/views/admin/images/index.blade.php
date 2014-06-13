@extends('bones::layouts.admin')

@section('main')

    <div class="row" id="upload-dropzone">
        <div class="col-md-3">

            <div class="list-group" data-spy="affix" data-offset-top="40">
                <div class="list-group-item">
                    <h4>Albums</h4>
                </div>

                @foreach($albums as $album)
                    <a href="#" onclick="loadAlbum({{$album->id}});" class="list-group-item album" id="album-{{$album->id}}" data-album-id="{{$album->id}}">
                        <span class="badge">{{$album->images()->count()}}</span>
                        {{$album->title}}
                    </a>
                @endforeach

                <div class="list-group-item album" id="album-delete" data-album-id="album-delete">
                    Delete
                </div>

                <div class="list-group-item">
                    <form action="{{URL::route('album_edit')}}" method="post">
                        <input type="text" class="form-control" name="title" placeholder="New album">
                    </form>
                </div>
            </div>

        </div>

        <div class="col-md-9" id="album-content">

            <h3>Loading...</h3>

        </div>
    </div>

    <div class="modal fade" id="upload-progress-modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">File upload</h4>
                </div>

                <div class="modal-body">
                    <dl class="dl-horizontal" id="upload-progress">
                        <dt>Filename</dt>
                        <dd>Progress</dd>
                        <hr style="margin: 5px 0px 10px 0px;">
                    </dl>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="image-preview-modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>

                <div class="modal-body">
                    <img src="" id="preview-img" style="width: 100%;" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection

@section('additional_js')

<script type="text/javascript">
    // Select the first album on page load
    $(function() {
        loadAlbum( $('.album').first().attr('data-album-id') );

        // Allow images to be dropped on albums
        $('.album').droppable({
            accept: '.image',
            // activeClass: 'list-group-item-info',
            hoverClass: 'list-group-item-info',
            drop: function( event, ui ) {

                var album_id = $( this ).addClass('list-group-item-warning').attr('data-album-id');
                var image_id = ui.draggable.attr('data-image-id');

                // Did we drop it on the delete album
                if (album_id == 'album-delete') {
                    if (confirm('Really delete this image? This cannot be undone.')) {
                        var url = '/admin/image/delete';
                    } else {
                        return;
                    }
                } else {
                    var url = '/admin/image/move';
                }

                // Animate it away
                ui.draggable.effect('scale', {percent: 0}, 500, function() {
                    $(this).remove();
                });

                $.post(url, {
                    image_id: image_id,
                    album_id: album_id
                }, function(count) {
                    $('#album-'+album_id).removeClass('list-group-item-warning');
                    $('#album-'+album_id).find('span').text(count);
                    $('#album-'+current_album_id).find('span').text( parseInt($('#album-'+current_album_id).find('span').text())-1 );
                });

            }
        });
    });

    // Function to load a different album
    var current_album_id;
    var dragging;
    function loadAlbum(id) {
        current_album_id = id;

        $('.album').removeClass('active');
        $('#album-'+id).addClass('active');

        $.get('/admin/images/album/'+current_album_id, function(html) {
            $('#album-content').html(html);

            // Make the images draggable
            $('#album-content .image').draggable({
                revert: 'invalid',
                zIndex: 999,
                start: function() {
                    dragging = true;
                },
                stop: function() {
                    setTimeout(function() {
                        dragging = false;
                    }, 1000);
                }
            });
        });
    }

    // We can deal with iframe uploads using this URL:
    var options = {input: false, logging: false};
    // 'zone' is an ID but you can also give a DOM node:
    var zone = new FileDrop('upload-dropzone', options)

    // Do something when a user chooses or drops a file:
    zone.event('send', function (files) {
        $('#upload-dropzone').removeClass('bg-warning');
        // FileList might contain multiple items.
        files.each(function (file, x) {
            if (file.type.indexOf('image/') !== 0) {
                alert('Only images may be uploaded');
                file.abort();
                return;
            }

            $('#upload-progress-modal').modal('show');

            $('#upload-progress').append('<dt>'+file.name+'</dt><dd><div class="progress"><div class="progress-bar" id="file-progress-'+x+'"></div></div></dd>');

            file.event('progress', function (current, total) {
                var width = current / total * 100 + '%'
                fd.byID('file-progress-'+x).style.width = width;
            });

            file.event('done', function() {
                loadAlbum(current_album_id);
            });

            file.event('error', function (e, xhr) {
                alert(xhr.status + ', ' + xhr.statusText)
            });

            // Send the file:
            file.sendTo('/admin/image/upload/'+current_album_id);
        })
    }).event('dragOver', function(evt) {
        $('#upload-dropzone').addClass('bg-warning');

    }).event('dragLeave', function(evt) {
        $('#upload-dropzone').removeClass('bg-warning');

    });

    // Open an image preview
    function preview(filename, url) {
        if (dragging) return;

        $('#image-preview-modal h4').text(filename);
        $('#image-preview-modal #preview-img').attr('src', url);

        $('#image-preview-modal').modal('show');
    }
</script>
@endsection