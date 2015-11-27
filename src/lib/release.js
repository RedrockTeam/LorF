
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
            data.species;
            if($("select").val() == '' || $('textarea').val() == '' || $('input[type=place]').val() =='' || $('input[type=date]').val() == ''){
                alert('请检查 不能为空');
                return;
            }

            function checked(el){
                return el.checked()
            }

            var flag = false;

            $('input[type=checkbox]').forEach(function(item,index){
                if(item.checked == true){
                    flag = true;
                    data.species = $('input[type=checkbox]').eq(index).val();
                    return;
                }
            })

            if(flag == false){
                alert('请选择发布种类');
                return;
            }


            date = $('input[type=date]').val();
            date = new Date(Date.parse(date.replace(/-/g, "/")));
            date = date.getTime();
            console.log(date);


            data.kind = $("select").val();
            data.content = $('textarea').val();
            data.date = date;
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

