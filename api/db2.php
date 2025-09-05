<?php
// ✅ 啟動 session：用來記錄登入狀態、瀏覽狀態等
session_start(); 

// ✅ 設定時區，避免時間存進資料庫時跑掉
date_default_timezone_set("Asia/Taipei");

// ✅ 分類代號 → 中文名稱對應表
$Type=[
  1=>'健康新知',
  2=>'菸害防治',
  3=>'癌症防治',
  4=>'慢性病防治'
];

// ✅ 除錯用：美化輸出陣列內容
function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

// ✅ 直接跑 SQL 語法 → 回傳查詢結果（全部）
function q($sql){
    $dsn='mysql:host=localhost;dbname=db18;charset=utf8';
    $pdo=new PDO($dsn,'root','');
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// ✅ 頁面跳轉：導向到指定網址
function to($url){
    header("location:".$url);
}

// ✅ DB 類別：用來操作資料表（CRUD）
class DB{
    private $dsn="mysql:host=localhost;dbname=db18;charset=utf8"; // 連線字串
    private $pdo;   // PDO 物件
    private $table; // 資料表名稱

    // 建構子：建立連線，並記住是哪張資料表
    function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,"root",'');
    }

    // all(): 取得多筆資料，可加條件或排序
    function all(...$arg){
        $sql="select * from $this->table ";
        if(isset($arg[0])){
            if(is_array($arg[0])){
                $tmp=$this->arraytosql($arg[0]);
                $sql.=" where ".join(" AND ",$tmp);
            }else{
                $sql.=$arg[0];
            }
        }
        if(isset($arg[1])){
            $sql.=$arg[1];
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // count(): 計算資料筆數
    function count(...$arg){
        $sql="select count(*) from $this->table ";
        if(isset($arg[0])){
            if(is_array($arg[0])){
                $tmp=$this->arraytosql($arg[0]);
                $sql.=" where ".join(" AND ",$tmp);
            }else{
                $sql.=$arg[0];
            }
        }
        if(isset($arg[1])){
            $sql.=$arg[1];
        }
        return $this->pdo->query($sql)->fetchColumn();
    }

    // sum(): 計算某欄位的總和
    function sum($col,...$arg){
        $sql="select sum($col) from $this->table ";
        if(isset($arg[0])){
            if(is_array($arg[0])){
                $tmp=$this->arraytosql($arg[0]);
                $sql.=" where ".join(" AND ",$tmp);
            }else{
                $sql.=$arg[0];
            }
        }
        if(isset($arg[1])){
            $sql.=$arg[1];
        }
        return $this->pdo->query($sql)->fetchColumn();
    }

    // find(): 找一筆資料（id 或 條件）
    function find($id){
        $sql="select * from $this->table ";
        if(is_array($id)){
            $tmp=$this->arraytosql($id);
            $sql.=" where ".join(" AND ",$tmp);
        }else{
            $sql.=" WHERE `id`='$id'";
        }
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // save(): 新增或更新資料
    function save($array){
        if(isset($array['id'])){
            // 有 id → update 更新
            $sql="update $this->table set ";
            $tmp=$this->arraytosql($array);
            $sql.=join(" , ",$tmp)." where `id`='{$array['id']}'";
        }else{
            // 沒 id → insert 新增
            $cols=join("`,`",array_keys($array));
            $values=join("','",$array);
            $sql="insert into $this->table (`$cols`) values('$values')";
        }
        return $this->pdo->exec($sql);
    }

    // del(): 刪除資料
    function del($id){
        $sql="delete from $this->table ";
        if(is_array($id)){
            $tmp=$this->arraytosql($id);
            $sql.=" where ".join(" AND ",$tmp);
        }else{
            $sql.=" WHERE `id`='$id'";
        }
        return $this->pdo->exec($sql);
    }

    // 工具：把陣列轉成 SQL 的 where 條件
    private function arraytosql($array){
        $tmp=[];
        foreach($array as $key=>$value){
            $tmp[]="`$key`='$value'";
        }
        return $tmp;
    }
}

// ✅ 建立資料表的操作物件
$User=new DB('users');
$Visit=new DB('visit');
$News=new DB('news');
$Que=new DB('que');
$Log=new DB('log');

// ✅ 訪客計數器：統計每天有多少人來
if(!isset($_SESSION['visit'])){
    $today=$Visit->find(['date'=>date("Y-m-d")]);
    if(empty($today)){
        // 今天第一次有人來 → 建新紀錄
        $Visit->save(['date'=>date("Y-m-d"),'visit'=>1]);
    }else{
        // 今天已經有人來過 → +1
        $today['visit']++;
        $Visit->save($today);
    }
    // 避免同一個人不斷刷新被重複計算
    $_SESSION['visit']=1;
}
?>
