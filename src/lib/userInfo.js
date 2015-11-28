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
                url:'http://hongyan.cqupt.edu.cn/LorF/index.php/Home/Index/nextPage',
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

                    var template =  kind == 0 ? $('#template').html():$('#template-1').html();
                    Mustache.parse(template);
                    var template_wrapper = kind == 0 ? $("#template-wrapper"):$("#template-wrapper-1");
                    var rendered = Mustache.render(template,nextPage);
                    template_wrapper.append(rendered);
                    kind == 0? from.solved+=interval : from.released+=interval;
                }
            })
        })




        //页面交互逻辑
        $('.solved').on('click',function(){
            show();
            $('.ensure').on('click',function(){
                hide()
            })

        })

        $('.cancel').on('click',function(){
           hide()
        })



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