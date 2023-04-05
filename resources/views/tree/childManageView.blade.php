
<ul id = "my_tree">
    @foreach($childs as $child)
    <li>
        <a id = "{{$child->id}}" onclick = "addCategory(this.id,'{{$child->title}}')">
            <i class="fa-solid fa-file-pen fa-lg" style="color: #0756f2;"></i></a>  {{$child->title}}
        @if(count($child->childs))
            <i class="show fa-solid fa-chevron-right"></i>
            @include('tree.childManageView', ['childs'=>$child->childs])
        @endif
    </li>
    @endforeach
</ul>
