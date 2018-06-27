var mycode = $(".keyCode")
var chekeCode = $("#inputCode")
var code = "";
var isTrue=''
$(document).ready(function () {
    createCode();
})
function createCode(){
    code='';
    var codeLength = 4;//验证码的长度
    var random = new Array(0,1,2,3,4,5,6,7,8,9);//随机数
    for(var i = 0; i < codeLength; i++) {
        //循环操作
        var index = Math.floor(Math.random()*10);//取得随机数的索引（0~35）
        code += random[index];//根据索引取得随机数加到code上
    }
    mycode.text(code);//把code值赋给验证码
}
chekeCode.blur(function () {
    chekeCode.val().toUpperCase();//取得输入的验证码并转化为大写
    if(chekeCode.val().toUpperCase() != code ) {
        //若输入的验证码与产生的验证码不一致时
        console.log( chekeCode.val().toUpperCase())
        $(".checkText").css({"display":"block"})
        createCode();//刷新验证码
        chekeCode.val('');
    }else {
        isTrue=1
    }
})
$('.keyCode').click(function () {
    chekeCode.val('');
    createCode();
})
