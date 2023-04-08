$.fn.extend({
    treed: function (o) {

        //inicjalizowanie drzewa
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });

        // dodawanie mozliwości przejscia poniżej poprzez klikniecie w poszczególne elementy

        tree.find('.branch>i.show').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    },

    // rozwiniecie/zwiniecie kazdego katalogu
    treedAll: function(o){
        var tree = $(this);
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.children().children().toggle();
        });
    }
});

function toggleAll(){
    $('#my_tree').treedAll()
}

$('#my_tree').treed()


