if($('#search-admin-input').length){
    $( "#search-admin-input" ).autocomplete({
        source: function( request, response ) {
            $.ajax( {
                url: base_url+'/search',
                data: {
                    term: $('#search-admin-input').val()
                },
                success: function( data ) {
                    response( data );
                }
            } );
        },
        minLength: 2,
        select: function (event, ui){
            if($.inArray(ui.item.id, products)){
                add_to_collection(ui.item.id);
                add_to_view(ui.item);

            }else{
                alert('dodałeś już ten przedmiot do kolekcji');
            }

        }
    } ).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li class='search_div col-md-3'>" )
            .append( "<img class='search_img w-100' src='"+item.img+"'><div class='overlay'><p>" + item.label +"<strong>"+item.price +" zł </strong></p></div> " )
            .appendTo( ul );
    };
}
