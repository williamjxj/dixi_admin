$(document).ready(function(){
    
    $(".TabbedPanelsContentGroup").css("background-color","#fff");
    
    $("tr.infoRow").next().css("background-color","#5492af").hide();
    $("tr.infoRow").next().find("td").css("border-right", "1px solid #d0d0d0");
    $("a.edit").click(function(){
        $(this).parents("tr").next().show();
        $("table#list_table tr td div").addClass("subForm");        
    }) 
    $("#list_thead tr.titleRow th:eq(0)").css("border-left", "1px solid #d0d0d0");
    $("tr td:first-child").css("border-left", "1px solid #d0d0d0");    
    $("tr.infoRow:odd td").css({"border-top" : "1px solid #eaeaea", "border-bottom" : "1px solid #eaeaea"});
    
    var sideH = $(".sidebarBox").height();    
    var winH = $(window).height();  
    function sideBarHeight() {
        if(sideH < winH){
            $(".sidebarBox").css("height",winH);
        }
    }
    $("ul.navList:eq(0)").slideDown(function(){sideBarHeight()});
    $(".navTitle:eq(0)").css("margin-top", "0").removeClass("nonActiveTitle").addClass("activeTitle");
    $(".navTitle").click(function(){
        var t = $(this);
        if(t.hasClass("activeTitle")){
            return false;
        } else {
            $(".sidebarBox .activeTitle").next().slideUp();
            $(".sidebarBox .activeTitle").removeClass("activeTitle").addClass("nonActiveTitle");
            t.removeClass("nonActiveTitle").addClass("activeTitle");
            t.next().slideDown();
        }
    }) 
    
//    var formWidth = $("#list_tbody form input.active").closest("form").width();
//    $("#list_tbody form input.active").css("width", (formWidth-22)+"px");  
//    $("#list_tbody form input.active").closest("form").css("width", formWidth+"px");
//    $("#list_tbody form input.active").closest("td").css("width", formWidth+"px");
    var formInputWidth = $("#list_tbody form input.active").width();
    $("#list_tbody form input.active").closest("form").css("width", (formInputWidth+22)+"px");
    $("#list_tbody form input.active").closest("td").css("width", (formInputWidth+22)+"px");
    
})