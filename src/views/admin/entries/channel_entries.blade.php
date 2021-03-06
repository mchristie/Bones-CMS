@extends('bones::layouts.admin')

@section('main')

    <div class="row">
        <div class="col-md-12">

            <h2 class="pull-left">{{$channel->title}}</h2>

            @if($channel->type == 'structured')
                <div class="pull-right">
                    <button class="btn btn-default" data-toggle="modal" data-target="#sort-modal">Sort</button>
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $entry)
                        <tr>
                            <td>
                                @if($channel->type == 'structured')
                                    @for($i = 1; $i < $entry->depth; $i++)
                                        - &nbsp;
                                    @endfor
                                @endif

                                {{$entry->title}}
                            </td>
                            <td>
                                @if($entry->status == \Christie\Bones\Libraries\Bones::STATUS_DRAFT)
                                    <span class="text-warning">{{$entry->status_title}}</span>

                                @elseif($entry->status == \Christie\Bones\Libraries\Bones::STATUS_PUBLISHED)
                                    <span class="text-success">{{$entry->status_title}}</span>

                                @elseif($entry->status == \Christie\Bones\Libraries\Bones::STATUS_DELETED)
                                    <span class="text-danger">{{$entry->status_title}}</span>

                                @else
                                    {{$entry->status_title}}
                                @endif
                            </td>
                            <td>
                                <a href="{{URL::route('entry_edit', $entry->id)}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    @if($channel->type == 'structured')
        <div class="modal fade" id="sort-modal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Sort {{$channel->title}}</h4>
                    </div>

                    <div class="modal-body">

                        <ul class="sortable">
                            @foreach($structured as $entry)
                                <li id="{{$entry->id}}">
                                    <div>
                                        <span class="glyphicon glyphicon-resize-vertical"></span>
                                        ({{$entry->id}}) {{$entry->title}}
                                    </div>

                                    @if($entry->children)
                                        <ul>
                                        @foreach($entry->children as $child)
                                            <li id="{{$child->id}}">
                                                <div>
                                                    <span class="glyphicon glyphicon-resize-vertical"></span>
                                                    ({{$child->id}}) {{$child->title}}
                                                </div>

                                                @if($child->children)
                                                    <ul>
                                                    @foreach($child->children as $granchild)
                                                        <li id="{{$granchild->id}}">
                                                            <div>
                                                                <span class="glyphicon glyphicon-resize-vertical"></span>
                                                                ({{$granchild->id}}) {{$granchild->title}}
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                @endif

                                            </li>
                                        @endforeach
                                        </ul>
                                    @endif

                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

@endsection

@section('additional_js')
<script>
    $('#sort-modal').on('show.bs.modal', function() {
        $('.sortable').nestable({
            'listNodeName':      'ul',
            'itemNodeName':      'li',
            'rootClass':         'ul',
            // 'listClass':         'dd-list',
            // 'itemClass':         'dd-item',
            // 'dragClass':         'dd-dragel',
            'handleClass':       'glyphicon',
            // 'collapsedClass':    'dd-collapsed',
            // 'placeClass':        'dd-placeholder',
            // 'emptyClass':        'dd-empty',
            'expandBtnHTML':     '', // '<button data-action="expand">Expand></button>',
            'collapseBtnHTML':   '', // '<button data-action="collapse">Collapse</button>'
        });
    });

    $('#sort-modal').on('hide.bs.modal', function() {

    });

    /*
    $('#sort-modal').on('show.bs.modal', function() {
        $('.sortable').nestedSortable({
            forcePlaceholderSize: true,
            handle: '.glyphicon',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels: 3,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: false,
            listType: 'ul',
            protectRoot: true,
            change: function(el, x) {
                // console.dir(x);
                // console.log('Relocated item');
            },
            stop: function(el, x) {
                console.log(el);
            }
        });
    });

    $('#sort-modal').on('hide.bs.modal', function() {
        var data = $('.sortable').nestedSortable('toArray', {startDepthCount: 0});
        console.log(data);
    });
    */

</script>
@endsection