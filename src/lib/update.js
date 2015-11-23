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

        var data = {};


        //修改信息 提交
        $('.button').eq(0).on('click',function(){
            if(!$('input[name=phonenumber]').val() && !$('input[name=qq]').val()){
                alert('QQ，电话至少来一个呗');
                return false;
            }

            if(!/1[3|5|7|8][0-9]{9}/.test($('input[name=phonenumber]').val())){
                alert('电话不对呀');
                return false;
            }

            data.key = 'redrockswzllhzwjp',
            data.real_name = $('input[name=name]').val();
            data.stu_name = $('input[name=stu-number]').val();
            data.phone = $('input[name=phonenumber]').val();
            $.ajax({
                type:'GET',
                url: 'handleInfo',
                data:data,
                success:function(res){
                    alert(res);
                }

            })

        })

    })
})