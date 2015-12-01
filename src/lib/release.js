require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick',
        mustache: 'tools/mustache.min',
        jquery: 'tools/jquery.min',
        DateTimePicker:'tools/DateTimePicker'
    },
    shim: {
        zepto: {
            exports: 'Zepto'
        },
        jquery: {
            exports: '$'
        }
    }
})


require(['fastclick','zepto','jquery','DateTimePicker'],function(FastClick,Zepto,$){
    Zepto(document).ready(function(){
        //绑定FastClick
        FastClick.attach(document.body);



        var release = true;
        var oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";



        Zepto("#back").on('click',function(){
            history.back();
        })


        Zepto('#release').on('click',function(){
            console.log(release);

            console.log(release);
            var data = {};
            data.species;
            if(Zepto("select").val() == '' || Zepto('textarea').val() == '' || Zepto('input[type=place]').val() =='' || Zepto('input[type=date]').val() == ''){
                alert('请检查 不能为空');
                return;
            }

            function checked(el){
                return el.checked();
            }

            var flag = false;

            Zepto('input[type=radio]').forEach(function(item,index){
                if(item.checked == true){
                    flag = true;
                    data.species = Zepto('input[type=radio]').eq(index).val();
                    return;
                }
            })

            if(flag == false){
                alert('请选择发布种类');
                return;
            }

            data.kind = Zepto("select").val();
            data.content = Zepto('textarea').val();
            data.date = Zepto('input[type=date]').val();
            data.place = Zepto('input[name=place]').val();
            data.phone = Zepto('input[name=phone]').val();
            data.qq = Zepto('input[name=QQ]').val();

            //console.log(data);
            if(release){
                Zepto.ajax({
                    type:'POST',
                    url:'handleInfo',
                    data:data,
                    success:function(res){
                        console.log(res);
                        release = false;
                        res.status === 0 ?alert('修改失败'):location.href = oauth2Url;
                    }

                })
            }else{
                alert('请勿提交两次');
                return;
            }

        })


    })
})
