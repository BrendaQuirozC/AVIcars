/*
* @Author: erikfer94
* @Date:   2018-10-08 18:05:13
* @Last Modified by:   erikfer94
* @Last Modified time: 2019-01-10 17:50:33
*/
$(".searchForm").submit(function(e){
    var search=$(this).find("input").val();
    search=search.trim();
    $(this).find("input").val(search);
    if(search==""){
        e.preventDefault();
        return false;
    }
});
$(".busquedaAvanzada").submit(function(e){
    var minprice=$(this).find(".minprice").val();
    var maxprice=$(this).find(".maxprice").val();
    var send=true;
    if(isNaN(minprice)&&minprice!=""){
        $(this).find(".minprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(minprice==""){
        minprice=0;
    }
    if(isNaN(maxprice)&&maxprice!=""){
        $(this).find(".maxprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(maxprice==""){
        maxprice=0;
    }
    if(minprice>maxprice){
        $(this).find(".minprice").closest(".form-group").addClass("has-error");
        $(this).find(".maxprice").closest(".form-group").addClass("has-error");
        send=false;
    }
    if(!send){
        e.preventDefault();
        return false;
    }
})
$(".busqueda-width .more-search").click(function(){
    var e=$(this);
    if(!e.hasClass("actioned")){
        e.addClass("actioned");
        var advanced=e.closest("form").siblings("div.advanced-search");
        if(advanced.hasClass("opened")){
            e.removeClass("opened");
            advanced.removeClass("opened");
            setTimeout(function(){
                advanced.css('visibility', 'hidden');

                e.removeClass("actioned");
            },400);
        }
        else{
            advanced.css('visibility', 'visible');
            advanced.addClass("opened");
            e.addClass("opened");
            setTimeout(function(){

                e.removeClass("actioned");
            },400);
        }
    }
})
function getYearsSearch(marca,modelo){
    $.ajax({
        url : "/php/catalogoAutos/getYearsForSearch.php",
        data : "marca="+marca+"&modelo="+modelo,
        async : false,
        type : "POST",
        success: function(msg){
            $(".anoSearch").html(msg);
        }
    })
}
$(".marcaSearch").change(function(){
    var modeloSelect=$(this).closest(".advanced-search").find(".modeloSearch");
    modeloSelect.find("option").addClass("hidden");
    modeloSelect.find("option").attr("disabled",true);
    modeloSelect.find("option[value=0]").attr("disabled",false);
    modeloSelect.find("option[value=0]").removeClass("hidden").prop("selected",true);
    var marca=$(this).val();
    if(marca!=0){
         modeloSelect.find("option[data-marca='"+marca+"']").removeClass("hidden");
         modeloSelect.find("option[data-marca='"+marca+"']").attr("disabled",false);

    }
});
$(".modeloSearch").change(function(){
    var marcaSelect=$(this).closest(".advanced-search").find(".marcaSearch");
    var modelo=$(this).val();
    var marca=$(this).find("option:selected").data("marca");
    if(modelo!=0){
        marcaSelect.find("option[value="+marca+"]").prop("selected",true);
        getYearsSearch(marca,modelo);
    }
    else{
        $(".anoSearch").html('<option value="0">Cualquiera</option>');
    }
});
$(".stateSearch").change(function(){
    var state=$(this).val();
    $.ajax({
        url : "/php/Busqueda/getTownsForSearch.php",
        data : "state="+state,
        async : false,
        type : "POST",
        success: function(msg){
            $(".townSearch").html(msg);
        }
    })
})