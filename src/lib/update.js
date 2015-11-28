/**
 * Created by liuhuizhi on 15/11/22.
 */

require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto'],function(FastClick,$){
    $(document).ready(function(){
        FastClick.attach(document.body);

        //提交数据
        var data = {};

        var oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";


        //修改信息 提交
        $('.button').eq(0).on('click',function(){
            if(!$('input[name=phonenumber]').val() && !$('input[name=qq]').val()){
                alert('QQ，电话至少来一个呗');
                return false;
            }

            if(!(/1[3|5|7|8][0-9]{9}/.test($('input[name=phonenumber]').val()))){
                alert('电话不对呀');
                return false;
            }

            data.key = 'redrockswzllhzwjp';
            data.real_name = $('input[name=name]').val();
            data.stu_num = $('input[name=stu-number]').val();
            data.phone = $('input[name=phonenumber]').val();
            data.qq = $('input[name=qq]').val();
            $.ajax({
                type:'GET',
                url: 'handleInfo',
                data:data,
                success:function(res){
                    res.status === 0 ?alert('修改失败'):location.href = oauth2Url;
                }

            })

        })


        $('#back').on('click',function(){
            history.back();
        })

    })
})