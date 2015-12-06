require.config({
    baseUrl: '/LorF/src/lib',
    paths: {
        zepto: 'zepto.min',
        swiper: 'tools/swiper',
        fastclick: 'tools/fastclick',
        mustache: 'tools/mustache.min',
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

        $('#date').on('click',function(){
            show();
        })

//日期选择开始

        var date = new Date();
        year = date.getFullYear();
        month = date.getMonth() + 1;
        day = date.getDate();

        $('.year').get(0).innerHTML = year;
        $('.month').get(0).innerHTML = month;
        $('.day').get(0).innerHTML = day;


        var getTime = function(){
            var select = [];
            select.push(year);
            select.push(month);
            select.push(day);
            console.log(select.toString());
            $('.datePicked').get(0).innerHTML =  select.join('-');
        }


        getTime();
        count = 0;
        $('.time-comp3').on('click','a',function(){
            var self = $(this);

            if($(this).get(0).id.indexOf('Add') != -1){
                var kindAdd = self.get(0).id.substring(0,1);
                count++;
                switch(kindAdd){
                    case 'y':
                        self.siblings('div').get(0).innerHTML++;
                        getTime();
                    case 'm':
                        if(self.siblings('div').get(0).innerHTML == 12) return;
                        self.siblings('div').get(0).innerHTML+1;
                        getTime();
                    case 'd':
                        if(self.siblings('div').get(0).innerHTML>30) return;
                        self.siblings('div').get(0).innerHTML++;
                        getTime();

                }
            }else{
                var kindSub = self.get(0).id.substring(0,1);
                switch (kindSub){
                    case 'y':
                        self.siblings('div').get(0).innerHTML-1;
                        getTime();
                    case 'm':
                        if(self.siblings('div').get(0).innerHTML == 1) return;
                        self.siblings('div').get(0).innerHTML-1;
                        getTime()
                    case 'd':
                        if(self.siblings('div').get(0).innerHTML == 1) return;
                        self.siblings('div').get(0).innerHTML--;
                        getTime();
                }
            }

        })


        $('.close').eq(0).on('click',function(){
            hide();
        })
        $('.cancel').eq(0).on('click',function(){
            hide();
        })

        function hide(){
            $('.datePicker').hide();
            $('.datePicker-wrapper').hide();
        }


        function show(){
            $('.datePicker').show();
            $('.datePicker-wrapper').show();
        }

        var rule = [1,3,5,7,8,10,12];

        $('.ensure').eq(0).on('click',function(){
            var selected = $('.datePicked').get(0).innerHTML;
            var arr = selected.split('-');
            if(arr[1] == 2 && arr[2]>27){
                alert('哪有这一天嘛');
                return;
            }else if(rule.indexOf(arr[1])==-1 && arr[2]==31){
                alert('哪有这一天嘛');
                return;
            }else{
                $('#date').val(arr.join('-'));
                hide();
            }
        })





//结束





        var release = true;
        var oauth2Url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx81a4a4b77ec98ff4&redirect_uri=http%3A%2F%2Fhongyan.cqupt.edu.cn%2FLorF%2Findex.php%2FHome%2FIndex%2Findex&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";



        $("#back").on('click',function(){
            //history.back();
            location.href = oauth2Url;
        })


        $('#release').on('click',function(){
            var data = {};

            if($("#kind").val() == '' || $("#species").val() == '' || $('textarea').val() == '' || $('input[type=place]').val() =='' || $('＃date').val() == ''){
                alert('请检查 不能为空');
                return;
            }



            data.kind = $("#kind").val();
            data.species = $('#species').val();
            data.content = $('textarea').val();
            data.date = $('#date').val();
            data.place = $('input[name=place]').val();
            data.phone = $('input[name=phone]').val();
            data.qq = $('input[name=QQ]').val();


            console.log(data);
            if(release){
                $.ajax({
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
