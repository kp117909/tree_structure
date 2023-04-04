<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">

    <link href = "{{url('css/style.css')}}" rel = "stylesheet"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"
    ></script>

    <!-- custom alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/3133d360bd.js" crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet"/>
</head>
<body>
<div class="relative d-flex items-center justify-content-center">
    <div class="container mt-5">
        <div class="row d-flex justify-content-end">
                <div class = "col-md-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="text-center">Lista katalogów</h4>
                    </div>
                    <ul id = "my_tree">
                        @foreach($categories as $category)
                            <li>
                                <a id = "{{$category->id}}" onclick = "addCategory(this.id,'{{$category->title}}')">
                                    <i class="fa-solid fa-file-pen fa-lg" style="color: #0756f2;"></i>
                                    </a>  {{$category->title}}
                                @if(count($category->childs))
                                    <i class="show fa-solid fa-chevron-right"></i>
                                    @include('tree.childManageView', ['childs'=>$category->childs])
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

</script>

<script src = "/js/javascript.js"></script>


</html>
