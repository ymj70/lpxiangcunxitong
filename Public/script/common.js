/*
 *  公共
 */

// 公共变量
    var baseUrl = '';
    console.log($('input[name=session]').val());
    var menuStatuStorage = $('input[name=session]').val();
    if (sessionStorage.getItem("menuStatus") != menuStatuStorage) {
        if (menuStatuStorage == 1) {
            $('.control').addClass('control-cur');
        } else {
            $('.control').removeClass('control-cur');
        }
    }

// 公共函数
    // 跳转页面
        function goHref(url) {
            loaders();
            url = url || '/';
            window.location.href = url;
        }
        function goBack(url){
            // loaders();
            url = url || '';
            if (url != '') {
                window.location.href = url;
            } else {
                window.history.go(-1);
            }
        }

    // url 获取参数
        function GetQueryString(name){
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null) return unescape(r[2]);
            return null;
        }

    // 空数据
        function getEmptyDom() {
            if ($('.empty-seat').length > 0) $('.empty-seat').remove();
            return '<div class="empty-seat">'+
                '<div class="empty-img">'+
                    '<img src="'+$('input[name=public]').val()+'/image/empty.png" alt="">'+
                '</div>'+
            '</div>';
        }

    // 加载遮罩层
        function loaders(el, w) {
            loadSuccess();
            w = w || '加载中...';
            el = el || 'body';
            var dom = '<div class="loaders">' +
                '<div class="loaders-box">' +
                    '<div class="ball-spin-fade-loader"></div>' +
                    '<div class="loaders-word">' + w + '</div>' +
                '</div>' +
            '</div>';
            $(el).append(dom);
        }
        // 加载成功
        function loadSuccess(){
            if($('.loaders').length > 0) $('.loaders').remove();
        }

    // 画廊
        function layerPhotos(el){
            el = el || ".body_img";
            layer.photos({
                photos: el,
                tab: function(pic, layero) {
                    // console.log(pic) //当前图片的一些信息
                }
            });
        }

    // 分页
        function showLaypage(count, limit){
            layui.use(['laypage'], function() {
                var laypage = layui.laypage;
                laypage.render({
                    elem: 'fenye',
                    count: count,
                    limit: limit,
                    layout: ['prev', 'page', 'next'],
                    jump: function(obj, first) {
                        if(!first){
                            if(typeof getData === "function") {
                                getData(obj.curr);
                            }
                        }
                    }
                });
            });
        }

    // listSize
        function listSize(){
            var psize = 10,
                formsHeight = $('.layui-forms')[0].clientHeight,
                FontSize = parseInt($('html').css('font-size'));
            if ($('.layui-forms').attr('type') == 'table_tr') {
                psize = ~~(formsHeight/($('.table_th')[0].clientHeight+FontSize));
            } else if ($('.layui-forms').attr('type') == 'td') {
                psize = ~~(formsHeight/($('.layui-table thead')[0].clientHeight+FontSize));
            } else {
                psize = 10;
            }
            return --psize;
        }

    // 导航条
        // 显隐导航条
        $('body').on('click', '.nav-title', function() {
            var el = $('.control');
            var stu = 1;
            if (el.hasClass('control-cur')) {
                el.removeClass('control-cur');
                stu = 1;
            } else {
                el.addClass('control-cur');
                stu = 2;
            }
            $.ajax({
                type: "post",
                url: $('input[name=menuStatus]').val(),
                data: {
                    menuStatus: stu
                },
                dataType: "json",
                success: function(data){
                    sessionStorage.setItem("menuStatus", stu);
                    // console.log('menuStatus--'+data);
                }
            });
        });
        // 切换导航条
        $('body').on('click', '.nav > ul >li', function() {
            $(this).addClass('nav-cur').siblings('.nav-cur').removeClass('nav-cur');
        });