
jQuery(document).ready(function(){

    //登录验证  1为空   2为错误	0为正确
    var validate={username:1,password:1}

	///// TRANSFORM CHECKBOX /////							
	jQuery('input:checkbox').uniform();

	///// LOGIN FORM SUBMIT /////
	//jQuery('#login').submit(function(){
	//
	//	if(jQuery('#username').val() == '' && jQuery('#password').val() == '') {
	//		jQuery('.nousername').fadeIn();
	//		jQuery('.nopassword').hide();
	//		return false;
	//	}
	//	if(jQuery('#username').val() != '' && jQuery('#password').val() == '') {
	//		jQuery('.nopassword').fadeIn().find('.userlogged h4, .userlogged a span').text(jQuery('#username').val());
	//		jQuery('.nousername,.username').hide();
	//		return false;;
	//	}
	//});

    //登录验证
    $("#login").submit(function(){
        if(validate.username==0 && validate.password==0){
            return true;
        }
        //验证用户名
        $("input[name='username']").trigger("blur");
        //验证密码
        $("input[name='password']").trigger("blur");
        return false;
    });

    //验证用户名
    $("input[name='username']").blur(function(){
        var username = $("input[name='username']");
        if(username.val().trim()==''){
            $("#noname").find("div").remove().end().append("<div class='loginmsg'>用户名不能为空.</div>");
            jQuery('.nousername').fadeIn();
            return ;
        }
        $.post(checkName,{username:username.val().trim()},function(stat){
            if(stat==1){
                validate.username=0;
                jQuery('.nousername').hidden();
            }else{
                $("#noname").find("div").remove().end().append("<div class='loginmsg'>用户名不存在.</div>");
                jQuery('.nousername').fadeIn();
            }
        });
    });

    //验证密码
    $("input[name='password']").blur(function(){
        var password = $("input[name='password']");
        var username = $("input[name='username']");
        if(username.val().trim()==''){
            return;
        }
        if(password.val().trim()==''){
            $("#noname").find("div").remove().end().append("<div class='loginmsg'>密码不能为空.</div>");
            jQuery('.nousername').fadeIn();
            return ;
        }
        $.post(checkPwd,{password:password.val().trim(),username:username.val().trim()},function(stat){
            if(stat==1){
                validate.password=0;
                jQuery('.nousername').hide();
            }else{
                $("#noname").find("div").remove().end().append("<div class='loginmsg'>密码不正确.</div>");
                jQuery('.nousername').fadeIn();
            }

        })
    })
	
	///// ADD PLACEHOLDER /////
	//jQuery('#username').attr('placeholder','Username');
	//jQuery('#password').attr('placeholder','Password');
});
