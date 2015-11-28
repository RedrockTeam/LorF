
require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick',
        mustache: 'tools/mustache.min'
    },
    shim: {
        zepto: {
            exports: '$'
        },
    }
})


require(['fastclick','zepto'],function(FastClick,$){
    $(document).ready(function(){
        //绑定FastClick
        FastClick.attach(document.body);

        var oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";

        $("#back").on('click',function(){
            history.back();
        })


        $('#release').on('click',function(){

            var data = {};
            data.species;
            if($("select").val() == '' || $('textarea').val() == '' || $('input[type=place]').val() =='' || $('input[type=date]').val() == ''){
                alert('请检查 不能为空');
                return;
            }

            function checked(el){
                return el.checked();
            }

            var flag = false;

            $('input[type=radio]').forEach(function(item,index){
                if(item.checked == true){
                    flag = true;
                    data.species = $('input[type=radio]').eq(index).val();
                    return;
                }
            })

            if(flag == false){
                alert('请选择发布种类');
                return;
            }


            //date = $('input[type=date]').val();
            //date = new Date(Date.parse(date.replace(/-/g, "/")));
            //date = date.getTime();
            //console.log(date);


            data.kind = $("select").val();
            data.content = $('textarea').val();
            data.date = $('input[type=date]').val();
            data.place = $('input[name=place]').val();
            data.phone = $('input[name=phone]').val();
            data.qq = $('input[name=QQ]').val();

            //console.log(data);
            $.ajax({
                url:'http://127.0.0.1/LorF/index.php/Home/Relace/handleInfo',
                type:'GET',
                data:data,
                dataType:'json',
                success:function(res){

                    console.log(res);
                    res === 0 ?alert('修改失败'):location.href = oauth2Url;
                }

            })

        })


    })
})

