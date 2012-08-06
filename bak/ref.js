// JavaScript Document
/*
.loadGIF {
    background: black url(/main/sub/css/images/general/loading.gif) left center no-repeat ;
}
*/
$('#parentView').on("click", "table tbody td:not(td:.button-column)", function(event){
 
    try{
 
        /*  Extract the Primary Key from the CGridView's clicked row.
            "this" is the CGridView's clicked column or <td>.
            Go up one parent - which gives you the row.
            Go down to child(1) - which gives you the first column,
            which contains the row's PK. */
        var gridRowPK = $(this).parent().children(':nth-child(1)').text();
 
        /*Display the loading.gif file via jquery and CSS*/
        $("#loadingPic").addClass("loadGIF");
 
        // Call the Ajax function to update the Child CGridView //
        var request = $.ajax({ 
          url: "UpdateChildGrid",
          type: "POST",
          cache: false,
          data: {parentID : gridRowPK}, 
          dataType: "html" 
        });
 
        request.done(function(response) { 
            try{
                /*since you are updating innerHTML, make sure the
                received data does not contain any javascript - for
                security reasons*/
                if (response.indexOf('<script') == -1){
                    /*update the view with the data received from the server*/
                    document.getElementById('childView').innerHTML = response;
                }
                else {
                    throw new Error('Invalid Javascript in Response - possible
                    hacking!');
                }
            }
            catch (ex){
                alert(ex.message); /*** Log to server when in production ***/
            }
            finally{
                /*Remove the loading.gif file via jquery and CSS*/
                $("#loadingPic").removeClass("loadGIF");
 
                /*clear the ajax object after use*/
                request = null;
            }
        });
 
        request.fail(function(jqXHR, textStatus) {
            try{
                throw new Error('Request failed: ' + textStatus );
            }
            catch (ex){
                alert(ex.message); /*** Log to server when in production ***/
            }
            finally{
                /*Remove the loading.gif file via jquery and CSS*/
                $("#loadingPic").removeClass("loadGIF");
 
                /*clear the ajax object after use*/
                request = null;
            }
        });
    }
    catch (ex){
        alert(ex.message); /*** Log to server when in production ***/
    }
});