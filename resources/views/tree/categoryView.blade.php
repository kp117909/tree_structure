<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">

    <link href = "{{url('css/style.css')}}" rel = "stylesheet"/>
    {{-- Jquery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Alerty custom oraz kit ikon -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/3133d360bd.js" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
</head>
<body>
<div class="relative d-flex items-center justify-content-center">
    <div class="container mt-5">
        <div class="row d-flex justify-content-end">
                <div class = "col-md-6">
                    <h4 class="text-center">Sortuj liste katalogów</h4>
                        <div class = "row">
                            <div class = "col-md-4">
                                <a href="{{ url('categoryIndex/order') }}" class="btn btn-primary pull-right">Rosnąco</a>
                            </div>
                            <div class = "col-md-4">
                                <a href="{{ url('categoryIndex/desc') }}" class="btn btn-primary pull-right">Malejąco</a>
                            </div>
                            <div class = "col-md-4">
                                <a href="{{ url('categoryIndex/none') }}" class="btn btn-primary pull-right">Brak sortowania</a>
                            </div>
                        </div>
                        <div class = "mt-4">
                            <div class = "row">
                                <div class = "col-md-6">
                                        <h4 class="text-center">Lista katalogów</h4>
                                </div>
                                <div class = "col-md-6">
                                    <button class="btn btn-primary profile-button" onclick ="toggleAll()">Rozwiń/Zwiń Katalogi</button>
                                </div>
                            </div>
                        </div>
                    <ul id = "my_tree">
                        <li>
                            <a id = "0">[Przenieś na górę]</a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a id = "{{$category->id}}" onclick = "addCategory(this.id,'{{$category->title}}')">
                                    <i class="fa-solid fa-file-pen fa-lg" style="color: #0756f2;"></i>
                                    </a>  {{$category->title}}
                                @if(count($category->childs))
                                    <i class="show fa-solid fa-chevron-right"></i>
                                    @if(session("sort_type") == 'none')
                                        @include('tree.childManageView', ['childs'=>$category->childs])
                                    @elseif(session("sort_type") == 'order')
                                        @include('tree.childManageView', ['childs'=>$category->childs_orderBy])
                                    @elseif(session("sort_type") == "desc")
                                        @include('tree.childManageView', ['childs'=>$category->childs_orderByDesc])
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class = "col-md-6">
                    <form action="{{ route('tree.addCategoryForm') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between b align-items-center">
                            <h4 class="text-center">Dodaj nową kategorię</h4>
                        </div>
                    @if ($errors->has('title_new'))
                            <span class="text-danger">Podaj Nazwe</span>
                        @endif
                        <div class="form">
                            <input type="text" id="title_new" name = "title_new" placeholder="Nazwa"  class="form-control" />
                        </div>
                        <div class="form-group">
                            <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Dodaj</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    //Podane funkcje znajduja się tutaj ponieważ, nie wiem dlaczego ajax nie łapał scieżek route w osobnym pliku
    //Dodawanie nowego katalogu
    function addCategory(id,title){
        Swal.fire({
            title: 'Panel Katalogu',
            icon: 'info',
            html:'<label>Dodaj element do danego katalogu o nazwie <b>['+title+']</b></label>' +
                ' <div class="col-md-12 mt-2">'+
                    '@if ($errors->has('title'))'+
                            '<span class="text-danger">Podaj Nazwe</span>'+
                    '@endif'+
                    '<div class="form center">'+
                    '<input type="text" id="title" name = "title" placeholder="Nazwa" class="form-control" />'+
                   ' </div>'+
                '</div>',
            showCloseButton: true,
            showCancelButton: true,
            showDenyButton: true,
            cancelButtonText: 'Wyjdź',
            confirmButtonText: 'Dodaj',
            denyButtonText: 'Edytuj',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('tree.addCategory') }}",
                    type: "GET",
                    dataType: 'json',
                    data: {id: id, title: document.getElementById("title").value},
                    success: function (response) {
                        location.reload();
                    },
                    error: function (error) {
                        Swal.fire('Nie udało sie dodać kategorii!', '', 'error');
                    },
                })
            }else if(result.isDenied){
                Swal.close();
                setTimeout(()=>editCategory(id, title), 250);
            }
        });
    }
    //Edytowanie katalogu w którym znajduja sie przyciski do usuwania
    function editCategory(id, title){
        Swal.fire({
            title: 'Panel edycji katalogu',
            icon: 'info',
            html: '<label>Zmień nazwe katalogu <b>['+title+']</b></label>' +
                '<div class="col-md-12 mt-2">'+
                    '@if ($errors->has('new_title'))'+
                    '<span class="text-danger">Podaj nazwę</span>'+
                    '@endif'+
                '<div class="form center">'+
                    '<input type="text" value ="'+title+'" id="new_title" name = "new_title" placeholder="Nazwa" class="form-control" />'+
                '</div>'+
                    '<div class="mt-2 text-center"><button onclick = "deleteButton('+id+', \'none\')"class="btn btn-danger profile-button" type="button">Usuń Katalog</button></div>'+
                    '<div class="mt-2 text-center"><button onclick = "deleteButton('+id+', \'all\')"class="btn btn-danger profile-button" type="button">Usuń Katalog z zawartością</button></div>'+
                '</div>',
            showCloseButton: true,
            showCancelButton: true,
            showDenyButton: true,
            cancelButtonText: 'Wyjdź',
            confirmButtonText: 'Potwierdź',
            denyButtonText: 'Powrót',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('tree.editCategory') }}",
                    type: "GET",
                    dataType: 'json',
                    data: {id: id, title: document.getElementById("new_title").value},
                    success: function (response) {
                        location.reload();
                    },
                    error: function (error) {
                        Swal.fire('Nie udało sie edytować kategori!', '', 'error');
                    },
                })
            }else if(result.isDenied){
                Swal.close();
                setTimeout(()=>addCategory(id, title), 250);
            }
        });
    }

    //Usuwanie katalogu "type" zależnie od tego czy chcemy usunąć katalog z elementami czy elementy zostawić i przypisać je
    // do katalogu wyżej
    function deleteButton(id, type){
        $.ajax({
            url: "{{ route('tree.deleteCategory') }}",
            type: "GET",
            dataType: 'json',
            data: {id: id, type:type},
            success: function (response) {
                console.log(response)
                location.reload();
            },
            error: function (error) {
                Swal.fire('Nie udało sie usunąć kategori!', '', 'error');
            },
        })
    }

    //Przenoszenie węzłów do innych gałęzi za pomocą drag and drop
    editPlace(document.getElementById("my_tree"))

    let my_elem = [];
    let index = 0;

    function editPlace (target) {

        let items = target.getElementsByTagName("li"), current = null;
        // Dodanie wszystkich elementów jako draggable
        for (let i of items) {

            i.draggable = true;

            i.ondragstart = e => {
                my_elem[index] = $(i.getElementsByTagName("a")).attr('id')
                index = index + 1;
                for (let it of items) {
                    if (it != current) { it.classList.add("hint"); }
                }
            };

            //Dodawanie i usuwanie wyglądu CSS
            i.ondragenter = e => {
                if (i != current) { i.classList.add("active"); }
            };

            i.ondragleave = () => i.classList.remove("active");

            i.ondragend = () => { for (let it of items) {
                it.classList.remove("hint");
                it.classList.remove("active");
            }};
            i.ondragover = e =>{
                e.preventDefault();
            }

            // Drop na dany katalog
            i.ondrop = e => {
                e.preventDefault();
                if (i != current) {
                    if(my_elem[0] == $(i.getElementsByTagName("a")).attr('id')){
                        temp_array = [];index = 0;my_elem = temp_array;
                        return
                    }
                    if (my_elem[0] != undefined) {
                        editElementPlace(my_elem[0], $(i.getElementsByTagName("a")).attr('id'));
                        temp_array = []
                        index = 0;
                        my_elem = temp_array;
                    }
                }
            };
        }
    }

    function editElementPlace(id, new_parent){
        $.ajax({
            url: "{{ route('tree.editPlace') }}",
            type: "GET",
            dataType: 'json',
            data: {id: id, new_parent:new_parent},
            success: function (response) {
                location.reload();
            },
            error: function (error) {
                Swal.fire(error.responseText, '', 'error');
            },
        })
    }

</script>

<script src = "/js/javascript.js"></script>
</html>
