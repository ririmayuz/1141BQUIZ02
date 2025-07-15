<div class="nav" style="margin-bottom: 20px;">
    目前位置：首頁 > 分類網誌 > <span id='NavType'>慢性病防治</span>
</div>

<fieldset style="width: 120px; display:inline-block; vertical-align:top;">

        <legend>分類網誌</legend>
        <div><a data-type='1' class="type-link">健康新知</a></div>
        <div><a data-type='2' class="type-link">菸害防治</a></div>
        <div><a data-type='3' class="type-link">癌症防治</a></div>
        <div><a data-type='4' class="type-link">慢性病防治</a></div>
    
</fieldset>
<fieldset style="width: 600px; display:inline-block">
    <legend>文章列表</legend>
    <div id="TypeList"></div>
    <div id="Post"></div>
</fieldset>

<script>
$(".type-link").on("click",function(){
    let type = $(this).text();
    $("#NavType").text(type);

    let typeID = $(this).data("type");
    $.get("api/get_type_list.php",{type:typeID},function(list){
        $("#Post").html("");
        $("#TypeList").html(list);
    })
})
</script>