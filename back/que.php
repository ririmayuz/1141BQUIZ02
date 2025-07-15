<fieldset>
    <legend>問卷調查</legend>
    <form action="./api/admin_que.php" method="post">
        <div style="display: flex;">
            <div style="width: 50%;">問卷名稱</div>
            <div style="width: 50%;">
                <input type="text" name="subject" id="subject" style="width:70%;">
            </div>
        </div>
        <div id="items">
            <div class="item">
                <label for="">選項</label>
                <input type="text" name="option[]" style="width:70%;">
                <button type="button" onclick='more()'>更多</button>
            </div>
        </div>
        <div class="ct">
            <input type="submit" value="新增"> | 
            <input type="reset" value="清空">
        </div>
    </form>
</fieldset>
<script>
    function more() {
        let item=
        `
        <div class="item">
            <label for="">選項</label>
            <input type="text" name="option[]" style="width:70%;">
            <button onclick='more()'>更多</button>
        </div>
        `
        // $(".item").last().after(item);
        $("#items").append(item);
      }
</script>