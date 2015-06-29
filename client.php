<html>
<head>
<title>ORZtobias之AJAX+SOCKET聊天室</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<style>
html,body{font:14px Open Sans, Microsoft JhengHei, Arial, serif;}
#log {width:300px; height:400px; border:10px solid #7F9DB9; overflow:auto;}
#msg {width:200px;border:3px solid #555555;}
.login_frame{border: solid 5px #45619D;width: 350px;height: 300px;margin: 0px auto;margin-top: 100px;border-radius: 10px;
text-align: center;  background-color: #fff;}
.main{width: 100%;height: 100%;position: absolute;left: 0px;top: 0px;z-index: -1;}
.fb_login{width:200px;padding: 10px 0px;margin-top:20px;  background-color:#45619D;font-size: 14px;color: #FFF;margin: 20px auto;}
.login{width: 100px;margin: 5px 0px;display: inline-block;font-size: 30px;font-weight: bold;border-bottom: solid 4px #c1bbb3;}
.normal_login{width:200px;}
.btn{width: 80px;margin: 20px auto;background-color: #766c61;padding: 5px 10px; color: #FFF;}
.btn:hover{background-color: #38342e;}
</style>

<script>

var photo = "https://www.facebook.com/profile.php?id=100000366850931&fref=ts";
var photo2 = photo;
var name = "";

var num=0; //num定義為chat和server互相確認之目前第幾句msg

var http_request =false;//看到這就知道AJAX在準備了

function send(){
var txt,msg;
txt = $("msg");
msg = txt.value;
if(!msg){ alert("訊息不能為全空白的啦"); return; }

str_num =num.toString()

//要傳出去給server的數據為複合資訊: 聊天訊息計數+區隔碼+輸入之訊息
msg=str_num+"^znl^"+msg; 
txt.value="";
//txt.focus();

try {
http_request = new XMLHttpRequest();
}

catch (failed)
{
http_request = false;
}

if (!http_request)
{
alert("Error initializing XMLHttpRequest!");
}

var url = "midterm.php?msg=" + msg;
http_request.open("GET", url, true);
http_request.send(null);
http_request.onreadystatechange = function() { alertContents(http_request); };

}

//====此段為接收server回傳值並進行處理後，再丟上螢幕顯示====

function alertContents(http_request) {
if (http_request.readyState == 4) {
if (http_request.status == 200) {

var doc = http_request.responseText;//從server傳回來的資訊

//將server傳回來之資訊:"聊天訊息序號"+區隔碼+"聊天訊息"，拆開來。
var pos1;
var pos2;
var sv_msg;
var leng;
var str_num;

leng=doc.length;

pos1=doc.indexOf("^znl^"); //位置1為聊天訊息序號的最後一碼位置

pos2=pos1+5;//位置2為聊天訊息的起始位置

str_num=doc.substring(0,pos1);//得到聊天訊息序號，不過這是字串

//這個真number再來是要傳給server的，表示最後讀取自server之訊息序號
num = parseInt(str_num) ;
console.log(num);
sv_msg=doc.substring(pos2,leng);//這就是server傳來的目前最後一則聊天訊息

//以下，pos2=leng，表示只傳回來這兩個:num^znl^，識別碼後面沒有東西了，不用做任何動作
//else則秀出聊天訊息

if (pos2==leng)
{}
else
{
log_show(sv_msg);
}
}

}

}
// setInterval("polling();", 5000) //每隔五秒發出一次查詢
polling();
console.log(num);
function polling(){
try {
http_request = new XMLHttpRequest();
}

catch (failed)
{
http_request = false;
}

if (!http_request)
{
alert("Error initializing XMLHttpRequest!");
}

//此乃輪詢者代號，嘿嘿!server收到這個就知道這不是聊天訊息，而是神秘輪詢者

var polling="!!!null!!!";

str_num =num.toString();//將數字轉成字串

polling=str_num+"^znl^"+polling;//組合成複合資訊，傳出給server詢問用

var url = "midterm.php?msg=" + polling;

http_request.open("GET", url, true);
http_request.send(null);
http_request.onreadystatechange = function() { alertContents(http_request); };

}

// Utilities 輸入介面相關設定
function $(id){ return document.getElementById(id); }
function log_show(msg){ $("log").innerHTML+=msg; }
function onkey(event){ if(event.keyCode==13){ send(); } } //這是enter 功能
</script>
</head>

<body>
<div id="fb-root"></div>
<script type="text/javascript" src="http://connect.facebook.net/zh_TW/all.js"></script>
<script>

	FB.init({
            appId: 585863394866930,
            status: true, 
            cookie: true,
            xfbml: true 
        });

    　　//getLoaginState();

function getLoaginState() {
	FB.getLoginStatus(function(response) { 
		if (response.authResponse) {					
			//同意授權並且登入執行這段
			FB.api('/me', function(response) {
      alert('我的Email為: '+response.email+'我的姓名為: '+ response.name);
					});
		}
		else {
			login();
		}
	});
}

function login() {
	FB.login(function(response) {
		if (response.authResponse) {
			//同意授權並且登入執行這段
			FB.api('/me', function(response) {
     			//alert('我的Email為: '+response.email+'我的姓名為: '+ response.name);
     			console.log(response);
     			var me = document.getElementById('me');
     		me.innerHTML='<a href='+response.link+' target="_blank">'+'<img src="https://graph.facebook.com/' 
            + response.id + '/picture" style="margin-right:5px"/>' 
            + response.name;
            photo = "https://graph.facebook.com/"+response.id+"/picture";
			});
		}
		else {
			alert("須同意應用程式才能進入此頁面");//不同意此應用程式
		}

	}, { scope:'email' });//要求存取Email
}	

function logout(){
	FB.logout(function(response) {
        // Person is now logged out
    });
}

</script>

<div class="login_frame">
	<div class="login">登入</div>
	
	<div class="fb_login" onclick="login();"><i class="fa fa-facebook"></i> 使用facebook登入</div>
	<div class="fb_logout" onclick="logout();"><i class="fa fa-facebook"></i> 使用facebook登出</div>
	or<br><br>
	<div>
		<input class="normal_login" type="text" placeholder="輸入暱稱">
	</div>
	<div class="btn">登入</div>
</div>
<div class="main">
	<div id="me"></div>
	<div id="log"></div>
	<input id="msg" type="textbox" onkeypress="onkey(event)"/>
	<button onclick="send()">Send</button>
</div>
</body>
</html>