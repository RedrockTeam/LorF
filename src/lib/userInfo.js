/**
 * Created by liuhuizhi on 15/11/21.
 */

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


require(['fastclick','zepto','swiper','mustache'],function(FastClick,$,swiper,Mustache){
    $(document).ready(function(){

        //绑定FastClick
        FastClick.attach(document.body);

        //$( "#datepicker" ).datepicker();

        var mySwiper = new Swiper('.swiper-container',{
            speed: 500,
            onSlideChangeStart: function(){
                $("nav .active").removeClass('active');
                $("nav div").eq(mySwiper.activeIndex).addClass('active');
            }
        });
        $("nav div").on('touchstart mousedown',function(e){
            e.preventDefault()
            $("nav div").removeClass('active');
            $(this).addClass('active');
            mySwiper.swipeTo( $(this).index() );
        }).click(function(e){
            e.preventDefault();
        });



        var from = {};
        from.released = from.solved = 4;


        $('.button').on('click',function(){
            var kind = undefined,
                self = this,
                interval = 4;

            $(this).hide();
            $("#loading").show();


            kind = $(this).attr('data-id');
            $.ajax({
                url:'http://localhost/LorF/index.php/Home/UserInfo/nextPage',
                type:'GET',
                data:{
                    key: 'redrockswzllhzwjp',
                    from: kind == 1 ? from.released :from.solved,
                    num: interval,
                    DorR: kind
                },
                dataType:'json',
                success:function(res){
                    if(res.nextPage.length == 0){
                        $("#loading").hide();
                        alert('木有了');

                        return false;
                    }
                    if(res.status == 0){
                        return false;
                    }
                    $(self).show();
                    $("#loading").hide();
                    var nextPage = res;
                    console.log(res);

                    var template =  kind == 1 ? $('#template').html():$('#template-1').html();
                    Mustache.parse(template);
                    var template_wrapper = kind == 1 ? $("#template-wrapper"):$("#template-wrapper-1");
                    var rendered = Mustache.render(template,nextPage);
                    template_wrapper.append(rendered);
                    kind == 1? from.released+=interval : from.solved+=interval;
                }
            })
        })





        $('#template-wrapper').on('click','span',function(e){
            e.stopPropagation();
            show();
            var dataId = $(this).parent().eq(0).attr('data-Id');
            var parent = $(this).parent().eq(0);
            $('.ensure').on('click',function(){
                hide();
                $.ajax({
                    url:'http://localhost/LorF/index.php/Home/UserInfo/handleDone',
                    type:'GET',
                    data:{
                        id:dataId
                    },
                    success:function(res){
                        if(res.status == 0){
                            alert('删除失败了sad')
                        }else{
                            parent.remove();
                        }
                    }
                })
            })
        })

        $('.cancel').on('click',function(){
           hide()
        })




        //$('.swiper-wrapper').eq(0).on('click','div',function(){
        //    console.log($(this).attr('data-id'));
        //})

        function show(){
            $('.shade').show();
            $('.banner').show();
        }


        function hide(){
            $('.shade').hide();
            $('.banner').hide()
        }
    })
})