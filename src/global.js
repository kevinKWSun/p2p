layui.use(['layer', 'form'], function () {
    var $ = layui.$
    , layer = layui.layer
	, form = layui.form;
	form.on('checkbox(selected-all)', function(sa){
		var child = $(sa.elem).parents('table').find('tbody input[type="checkbox"]');  
		child.each(function(index, item){
			item.checked = sa.elem.checked;
		});
		form.render();
	});
	form.on('checkbox(demofx)', function(data){
		var child = $(data.elem).parent().parent().find('input[type="checkbox"]');  
		child.each(function(index, item){
			item.checked = data.elem.checked;
		});
		form.render();
	});
    var active = {
        doAdd: function () {
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
            else {
                layer.msg('链接错误！');
				layer.closeAll();
            }
        },
        doEdit: function () {
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
            else {
                layer.msg('链接错误！');
				layer.closeAll();
            }
        },
        doGoBack: function () {
            history.go(-1);
        },
        doGoTop: function () {
            $(this).click(function () {
                $('body,html').animate({ scrollTop: 0 }, 500);
                return false;
            });
			layer.closeAll();
        },
        doRefresh: function () {
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
            else {
                location.href = location.href;
            }
        },
        doShow: function () {
			layer.closeAll();
            var url = $(this).data('url');
            if (url) {
                layer.open({
                    type: 2,
                    title: '详情',
                    //shadeClose: true,
                    shade: 0.1,
                    maxmin: true,
                    area: ['90%', '90%'],
                    fixed: true,
                    content: url
                });
            }
            else {
                layer.msg('链接错误！');
            }
        },
        /* doDelete: function () {
            var url = $(this).data('url');
            if (url) {
                //查出选择的记录
                if ($(".layui-table tbody input:checked").size() < 1) {
                    layer.msg('对不起，请选中您要操作的记录！');
                    return false;
                }
                var ids = "";
                var checkObj = $(".layui-table tbody input:checked");
                for (var i = 0; i < checkObj.length; i++) {
                    if (checkObj[i].checked && $(checkObj[i]).attr("disabled") != "disabled")
                        ids += $(checkObj[i]).attr("ids") + ','; //如果选中，将value添加到变量idlist中    
                }
                var data = { "ids": ids };
                layer.msg('确认删除这些信息？', '此操作不可逆，请再次确认是否要操作。', url, 'post', 'json', data);
            }
            else {
                layer.msg('链接错误！');
            }
        }, */
        doAction: function () {
            var url = $(this).data('url');
            if (url) {
                //查出选择的记录
                if ($(".layui-table tbody input:checked").size() < 1) {
					layer.closeAll();
                    layer.msg('对不起,请选中您要操作的记录！');
                    return false;
                }
                var ids = "";
                var checkObj = $(".layui-table tbody input:checked");
                if(checkObj.length > 1){
                    for (var i = 0; i < checkObj.length; i++) {
                        if (checkObj[i].checked && $(checkObj[i]).attr("disabled") != "disabled"){
                            ids += $(checkObj[i]).attr("ids") + ','; //如果选中，将value添加到变量idlist中   
                        } 
                    }
                }else{
                    if (checkObj[0].checked && $(checkObj[0]).attr("disabled") != "disabled"){
                        ids += $(checkObj[0]).attr("ids") + ',';
                    }
                }
                var data = { "ids": ids };
                $.ajax({
					url: url,
					type: 'post',
					dataType: 'json',
					data: data,
					success: function (data, startic) {
						layer.msg(data.message);
						if (data.state == 1) {
							location.href = location.href;
						}
					},
					error: function () {
						layer.msg('超时错误！');
					}
				});
            }
            else {
                layer.msg('链接错误！');
            }
			layer.closeAll();
        }
    };
    $('.do-action').on('click', function (e) {
		layer.load(1);
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
		layui.stope(e);
    });
	form.on('submit(doPostAll)', function (data) {
        var url = $(this).data('url');
        if (url) {
            var title = "", name = "";
            $('input[name=title]').each(function(){
                if($(this).val()){
                    title += $(this).val() + ',';
                }else {
                    layer.msg('请完善必填项', '提示');
                }
            });
            $('input[name=name]').each(function(){
                name += $(this).val() + ',';
            });
            var gids = $('#gids').val();
            var type = data.field.type;
            var data = {gids: gids,name: name,title: title, type: type};
			layer.load(1);
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data,
                success: function (data, startic) {
					layer.msg(data.message);
                    if (data.state == 1) {
                        location.href = data.url;
                    }
                },
                beforeSend: function () {
                   $(data.elem).attr("disabled", "true").text("保存中...");
                },
                complete: function () {
                   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#xe616;</i>提交');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg(textStatus);
                }
            });
			layer.closeAll();
        } else {
            layer.msg('链接错误！');
        }
        return false;
    });
	form.on('submit(solor)', function (data) {
		var keywords = data.field.skey;
		var url = $(this).data('url');
		layer.load(1);
		$.post(url, { keywords: keywords },
		function (result, status) {
			if (result.state == 1) {
				window.location.href = url + '?query=' + result.message;//result.data;
			}else{
				layer.msg(result.message);
				layer.closeAll();
			}
		});
	});
	form.on('submit(doPostParent)', function (data) {
		var index = parent.layer.getFrameIndex(window.name);
        var url = $(this).data('url');
        if (url) {
			layer.load(1);
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'json',
				data: data.field,
				success: function (data, startic) {
					layer.msg(data.message);
					if (data.state == 1) {
						parent.location.href = data.url;
						parent.layer.close(index);
					}
				},
				beforeSend: function () {
				   $(data.elem).attr("disabled", "true").text("保存中...");
				},
				complete: function () {
				   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#x1005;</i>提交');
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					layer.msg(textStatus);
				}
			});
			layer.closeAll();
        } else {
            layer.msg('链接错误！');
        }
        return false;
    });
    form.on('submit(doPost)', function (data) {
        var url = $(this).data('url');
        if (url) {
            if (data.field.pd != data.field.pd2) {
                t = false;
                layer.msg('两次密码不一致');
                return;
            }
			layer.load(1);
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'json',
				data: data.field,
				success: function (data, startic) {
					layer.msg(data.message);
					if (data.state == 1) {
						location.href = data.url;
					}
				},
				beforeSend: function () {
				   $(data.elem).attr("disabled", "true").text("保存中...");
				},
				complete: function () {
				   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#x1005;</i>提交');
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					layer.msg(textStatus);
				}
			});
			layer.closeAll();
        } else {
            layer.msg('链接错误！');
        }
        return false;
    });
	form.on('submit(doPostg)', function (data) {
        var url = $(this).data('url');
        if (url) {
            var ids = "";
            var checkObj = $("dl input:checked");
            if(checkObj.length >= 1){
                for (var i = 0; i < checkObj.length; i++) {
                    if (checkObj[i].checked && $(checkObj[i]).attr("disabled") != "disabled"){
                        ids += $(checkObj[i]).attr("ids") + ',';
                    }
                }
            }
            var name = $('#name').val();
            if($('#id').val()){
                var data = {ids: ids,name: name, id: $('#id').val()};
            }else{
                var data = {ids: ids,name: name};
            }
			layer.load(1);
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data,
                success: function (data, startic) {
					layer.msg(data.message);
                    if (data.state == 1) {
                        location.href = data.url;
                    }
                },
                beforeSend: function () {
                   $(data.elem).attr("disabled", "true").text("保存中...");
                },
                complete: function () {
                   $(data.elem).removeAttr("disabled").html('<i class="layui-icon">&#xe616;</i>提交');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg(textStatus);
                }
            });
			layer.closeAll();
        } else {
            layer.msg('链接错误！');
        }
        return false;
    });
	
	$('.a_add').on('click',function(){
		var num = $(this).attr('data');
		if(num > 9){
			alert('最多添加10条！');
		} else {
			var obj = $('.aid').clone();
			$(this).attr('data', (num*1+1));
			obj.removeClass('aid');
			obj.addClass('aid'+(num*1+1));
			$('.zt').before(obj);
		}
		
	});
	$('.a_del').on('click',function(){
		var num = $(this).siblings('.a_add').attr('data');
		if(num > 0){
			$('.aid'+num).remove();
			$(this).siblings('.a_add').attr('data', (num*1-1));
		} 
	});
	$('.layui-laypage-btn').on('click', function() {
		var obj = $('.layui-laypage-skip input');
		var min = parseInt(obj.attr('min'));
		var max = parseInt(obj.attr('max'));
		var href = obj.attr('data-href');
		var val = parseInt(obj.val());
		if(val < min || val > max) {
			layer.msg('页面不存在', {'icon':6, 'time': 1000});
		} else {
			location.href = href + '/' + val;
		}
	});
});