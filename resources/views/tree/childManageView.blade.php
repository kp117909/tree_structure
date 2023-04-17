
<ul id = "my_tree">
    @foreach($childs as $child)
    <li>
        <a id = "{{$child->id}}" onclick = "addCategory(this.id,'{{$child->title}}')">
            <i class="fa-solid fa-file-pen fa-lg" style="color: #0756f2;"></i>
        </a>
        {{$child->title}}
        @if(!$loop->first)
            <a id = "{{$child->id}}" href="{{ route('tree.arrowSorting', ['id' => $child->id, 'type' =>'up']) }}">
                <i class="fa-solid fa-arrow-up" style="color: #0756f2;"></i>
            </a>
        @endif
        @if(!$loop->last)
            <a id = "{{$child->id}}" href="{{ route('tree.arrowSorting',['id' => $child->id, 'type' =>'down']) }}">
                <i class="fa-solid fa-arrow-down" style="color: #0756f2;"></i>
            </a>
        @endif
        @if(count($child->childs))
            <i class="show fa-solid fa-chevron-right"></i>
            @if(session("sort_type") == 'none')
                @include('tree.childManageView', ['childs'=>$child->childs])
            @elseif(session("sort_type") == 'order')
                @include('tree.childManageView', ['childs'=>$child->childs_orderBy])
            @elseif(session("sort_type") == "desc")
                @include('tree.childManageView', ['childs'=>$child->childs_orderByDesc])
            @elseif(session("sort_type") == "special")
                @include('tree.childManageView', ['childs'=>$child->childs_specialSorting])
            @elseif(session("sort_type") == "arrow")
                @include('tree.childManageView', ['childs'=>$child->childs_arrowSorting])
            @endif
        @endif
    </li>
    @endforeach
</ul>
