@foreach(\App\Models\Parentable::getAllParents() as $parent)
    @if($parent->getSelfName())
        @php($class = 'level_'.$parent->combine_name)
        @include('tree_files.'.$file, ['parent' => $parent,'level' => 0,'class' => $class ])
    @endif
@endforeach
