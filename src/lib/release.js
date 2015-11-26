
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

        $("#back").on('click',function(){
            history.back();
        })


        $('#release').on('click',function(){

            var data = {};
            if($("select").val() == '' || $('textarea').val() == '' || $('input[type=place]').val() =='' || $('input[type=date]').val() == ''){
                alert('请检查 不能为空');
            }

            //if(!(/1[3|5|7|8][0-9]{9}/.test($('input[name=phone]').val()))){
            //    alert('电话不对呀');
            //    return false;
            //}

            data.kind = $("select").val();
            data.content = $('textarea').val();
            data.date = $('input[name=date]').val();
            data.place = $('input[name=place]').val();
            data.phone = $('input[name=phone]').val();
            data.qq = $('input[name=QQ]').val();

            console.log(data);
            $.ajax({
                url:'',
                type:'GET',
                data:data,
                dataType:'json',
                success:function(res){
                    alert('修改成功');
                }

            })

        })


    })
})

