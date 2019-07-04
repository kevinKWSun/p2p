layui.define(function(exports){
  var $ = layui.jquery
  ,laytpl = layui.laytpl
  ,element = layui.element
  ,setter = layui.setter
  ,device = layui.device()
  
  ,$win = $(window), $body = $('body')
  ,container = $('#'+ setter.container)
  
  ,SHOW = 'layui-show', HIDE = 'layui-hide', THIS = 'layui-this', TEMP = 'template', LAY_BODY = 'LAY_app_body'
  ,APP_FLEXIBLE = 'LAY_app_flexible', ICON_SHRINK = 'layui-icon-shrink-right', ICON_SPREAD = 'layui-icon-spread-left'
  ,SIDE_SHRINK = 'layadmin-side-shrink', SIDE_MENU1 = 'LAY_menuItemElem', SIDE_MENU2 = 'LAY_menuContentElem'
  
  //异常提示
  ,Error = function(content, options){
    return admin.popup($.extend({
      content: content
      ,maxWidth: 300
      ,shade: 0.01
      ,offset: 't'
      ,anim: 6
      ,id: 'LAY_adminError'
    }, options))
  }
  
  //通用方法
  ,admin = {
    v: '1.0.0-beta2'
    
    //建立视图
    ,view: function(id){
      return new View(id);
    }
    
    //数据的异步请求
    ,req: function(url, data, success, options){
      var that = this
      ,type = typeof data === 'function'
      ,response = setter.response;
      
      if(type){
        options = success
        success = data;
        data = {};
      }

      options = options || {};

      return $.ajax({
        type: options.type || 'get'
        ,dataType: options.dataType || 'json'
        ,data: data
        ,url: url
        ,success: function(res){
          if(res[response.statusName] !== response.statusCode) {
            var error = '<cite>Error：</cite> ' + (res[response.msgName] || '返回信息异常') + ' <br><cite>URL：</cite>' + url;
            options.error ? options.error(error) : Error(error);
          }
          success && success(res);
        }
        ,error: function(e, code){
          var error = '请求异常，请重试<br><cite>错误信息：</cite>'+ code +'<br><cite>URL：</cite>' + url;
          Error(error);
          options.error && options.error(error);
        }
      });
    }
    
    //加载中
    ,loading: function(elem){
      elem.append(
        this.elemLoad = $('<i class="layui-anim layui-anim-rotate layui-anim-loop layui-icon layui-icon-loading layadmin-loading"></i>')
      );
    }
    
    //移除加载
    ,removeLoad: function(){
      this.elemLoad && this.elemLoad.remove();
    }
    
    //屏幕类型
    ,screen: function(){
      var width = $win.width()
      if(width >= 1200){
        return 3; //大屏幕
      } else if(width >= 992){
        return 2; //中屏幕
      } else if(width >= 768){
        return 1; //小屏幕
      } else {
        return 0; //超小屏幕
      }
    }
    
    //侧边伸缩
    ,sideFlexible: function(status, tpl){
      var app = $('#'+ setter.container)
      ,iconElem =  $('#'+ APP_FLEXIBLE);
      
      //如果没有二级菜单，则阻止操作
      if(!tpl && admin.iconFlexible('length')) return;
      
      //设置状态
      if(status === 'spread'){
        if(admin.screen() < 2){
          app.addClass('layadmin-side-spread-sm');
        } else {
          app.removeClass(SIDE_SHRINK);
        }
        iconElem.removeClass(ICON_SPREAD).addClass(ICON_SHRINK);
      } else {
        if(admin.screen() < 2){
          app.removeClass('layadmin-side-spread-sm');
        }
        app.addClass(SIDE_SHRINK);
        iconElem.removeClass(ICON_SHRINK).addClass(ICON_SPREAD);
      }
      
      layui.event.call(this, setter.MOD_NAME, 'side', {
        status: status
      });
    }
    
    //通过检查是否有二级菜单，才显示/隐藏"伸缩的icon"
    ,iconFlexible: function(length){
      var iconFlexible = $('.layadmin-flexible')
      ,menuShow = $('#'+ SIDE_MENU2).find('.layui-menu-item.layui-show')
      ,notMenu3 = menuShow.find('.layui-nav-item').length === 0;
      
      length ||iconFlexible[notMenu3 ? 'addClass' : 'removeClass'](HIDE);
      return notMenu3;
    }
    
    //事件监听
    ,on: function(events, callback){
      return layui.onevent.call(this, setter.MOD_NAME, events, callback);
    }
    
    //弹出面板
    ,popup: function(options){
      var success = options.success
      ,skin = options.skin;
      
      delete options.success;
      delete options.skin;
      
      return layer.open($.extend({
        type: 1
        ,title: '提示'
        ,content: ''
        ,id: 'LAY_adminPopup'
        ,skin: 'layui-layer-admin' + (skin ? ' ' + skin : '')
        ,shadeClose: true
        ,closeBtn: false
        ,success: function(layero, index){
          var elemClose = $('<i class="layui-icon" close>&#x1006;</i>');
          layero.append(elemClose);
          elemClose.on('click', function(){
            layer.close(index);
          });
          typeof success === 'function' && success.apply(this, arguments);
        }
      }, options));
    }
    
    //右侧面板
    ,popupRight: function(options){
      //layer.close(admin.popup.index);
      return admin.popup.index = layer.open($.extend({
        type: 1
        ,id: 'LAY_adminPopupR'
        ,anim: -1
        ,title: false
        ,closeBtn: false
        ,offset: 'r'
        ,shade: 0.1
        ,shadeClose: true
        ,skin: 'layui-anim layui-anim-rl layui-layer-adminRight'
        ,area: '300px'
      }, options));
    }
    
    //主题设置
    ,theme: function(options){
      var theme = setter.theme
      ,local = layui.data(setter.tableName)
      ,id = 'LAY_layadmin_theme'
      ,style = document.createElement('style')
      ,styleText = laytpl([
        //主题色
        '.layui-header{background-color:{{d.color.header}} !important;}'
        ,'.layui-side-menu{background-color:{{d.color.side}} !important;}'
        
        ,'{{# if(d.color.alias !== "default"){ }}'
          ,'.layadmin-shortcut li .layui-icon{background-color:#F8F8F8 !important; color: #333;}'
        ,'{{# } }}'
        
        //侧边菜单图标
        ,'{{# if(d.hideSideIcon){ }}'
          ,'.layui-side-menu li{padding: 15px 0;}'
          ,'.layui-side-menu li .layui-icon{display: none}'
        ,'{{# } }}'
        
      ].join('')).render(options = $.extend({}, local.theme, options))
      ,styleElem = document.getElementById(id);
      
      //添加主题样式
      if('styleSheet' in style){
        style.setAttribute('type', 'text/css');
        style.styleSheet.cssText = styleText;
      } else {
        style.innerHTML = styleText;
      }
      style.id = id;
      
      styleElem && $body[0].removeChild(styleElem);
      $body[0].appendChild(style);
      $body.attr('layadmin-themealias', options.color.alias);
      
      //本地存储记录
      local.theme = local.theme || {};
      layui.each(options, function(key, value){
        local.theme[key] = value;
      });
      layui.data(setter.tableName, {
        key: 'theme'
        ,value: local.theme
      }); 
    }
    
    //……
  }
  
  //视图控制
  ,View = function(id){
    this.id = id;
    this.container = $('#'+(id || LAY_BODY));
  };

  //请求模板文件渲染
  View.prototype.render = function(view){
    var that = this, router = layui.router();
    view = setter.views + view + setter.engine;
    
    admin.loading(that.container); //loading
    
    //请求模板
    $.ajax({
      url: view
      ,dataType: 'html'
      ,success: function(html){
        that.parse('<div>' + html + '</div>');

        if(that.then){
          that.then(html);
          delete that.then; 
        }
        
        delete admin.prevErrorRouter[router.path[0]];
      }
      ,error: function(e){
        admin.removeLoad();
        admin.prevErrorRouter[router.path[0]] = layui.router().href;
        
        if(that.render.isError) return Error('请求视图文件异常，状态：'+ e.status);
        if(e.status === 404){
          that.render('template/tips/404');
        } else {
          that.render('template/tips/error');
        }
        that.render.isError = true;
      }
    });
    return that;
  };
  
  //解析模板
  View.prototype.parse = function(html, refresh, callback){
    var that = this
    ,isScriptTpl = typeof html === 'object' //是否模板元素
    ,elem = isScriptTpl ? html.children() : $(html)
    ,elemTemp = isScriptTpl ? html : elem.find('*[template]')
    ,fn = function(options){
      var tpl = laytpl(options.dataElem.html());
      options.dataElem.after(tpl.render(options.res || {}));
      
      admin.iconFlexible(); //根据二级菜单情况显示/隐藏icon
      if(admin.screen() < 2){
        admin.sideFlexible();
      }
      typeof callback === 'function' && callback();
      
      try {
        options.done && new Function('d', options.done)(options.res);
      } catch(e){
        console.error(options.dataElem[0], '\n存在错误回调脚本\n\n', e)
      }
    };
    
    if(refresh){
      that.container.after(elem);
    } else {
      that.container.html(elem);
    }
    
    //遍历模板区块
    for(var i = elemTemp.length; i > 0; i--){
      (function(){
        var dataElem = elemTemp.eq(i - 1)
        ,layDone = dataElem.attr('lay-done') //获取回调
        ,url = dataElem.attr('lay-url') //获取绑定的数据接口
        
        if(url){
          admin.req(url, dataElem.data('where') || {}, function(res){
            fn({
              dataElem: dataElem
              ,res: res
              ,done: layDone
            });
          }, {
            type: dataElem.attr('lay-method')
          });
        } else {
          fn({
            dataElem: dataElem
            ,done: layDone
          });
        }
      }());
    }
    
    return that;
  };
  
  //直接渲染字符
  View.prototype.send = function(view, data){
    var tpl = laytpl(view || this.container.html()).render(data || {});
    this.container.addClass(SHOW).html(tpl);
    return this;
  };
  
  //局部刷新模板
  View.prototype.refresh = function(callback){
    var that = this
    ,next = that.container.next()
    ,templateid = next.attr('lay-templateid');
    
    if(that.id != templateid) return that;
    
    that.parse(that.container, 'refresh', function(){
      that.container.siblings('[lay-templateid="'+ that.id +'"]:last').remove();
      typeof callback === 'function' && callback();
    });
    
    return that;
  };
  
  //回调
  View.prototype.then = function(callback){
    this.then = callback;
    return this;
  };
  
  //事件
  var events = admin.events = {
    //伸缩
    flexible: function(othis){
      var iconElem = othis.find('#'+ APP_FLEXIBLE)
      ,isSpread = iconElem.hasClass(ICON_SPREAD);
      admin.sideFlexible(isSpread ? 'spread' : null);
    }
    
    //刷新
    ,refresh: function(){
      layui.index.render();
    }
    
    //点击消息
    ,message: function(othis){
      othis.find('.layui-badge-dot').remove();
    }
    
    //弹出主题面板
    ,theme: function(){
      admin.popupRight({
        id: 'LAY_adminPopupTheme'
        ,success: function(){
          admin.view(this.id).render('system/theme')
        }
      });
    }
    
    //便签
    ,note: function(othis){
      var mobile = admin.screen() < 2
      ,note = layui.data(setter.tableName).note;
      
      events.note.index = admin.popup({
        title: '便签'
        ,shade: 0
        ,offset: [
          '58px'
          ,(mobile ? null : (othis.offset().left - 250) + 'px')
        ]
        ,anim: -1
        ,id: 'LAY_adminNote'
        ,skin: 'layadmin-note layui-anim layui-anim-upbit'
        ,content: '<textarea placeholder="内容"></textarea>'
        ,resize: false
        ,success: function(layero, index){
          var textarea = layero.find('textarea')
          ,value = note === undefined ? '便签中的内容会存储在本地，这样即便你关掉了浏览器，在下次打开时，依然会读取到上一次的记录。是个非常小巧实用的本地备忘录' : note;
          
          textarea.val(value).focus().on('keyup', function(){
            layui.data(setter.tableName, {
              key: 'note'
              ,value: this.value
            });
          });
        }
      })
    }
    
    //弹出关于面板
    ,about: function(){
      admin.popupRight({
        id: 'LAY_adminPopupAbout'
        ,success: function(){
          admin.view(this.id).render('system/about')
        }
      });
    }
    
    //弹出更多面板
    ,more: function(){
      admin.popupRight({
        id: 'LAY_adminPopupMore'
        ,success: function(){
          admin.view(this.id).render('system/more')
        }
      });
    }
    
    //返回上一页
    ,back: function(){
      history.back();
    }
    
    //主题设置
    ,setTheme: function(othis){
      var theme = setter.theme
      ,index = othis.data('index')
      ,nextIndex = othis.siblings('.layui-this').data('index');
      
      if(othis.hasClass(THIS)) return;
      
      othis.addClass(THIS).siblings('.layui-this').removeClass(THIS);
      
      if(theme.color[index]){
        theme.color[index].index = index
        admin.theme({
          color: theme.color[index]
        });
      }
    }
  };
  
  //初始
  !function(){
    //主题初始化
    var local = layui.data(setter.tableName);
    local.theme && admin.theme(local.theme);
    
    //禁止水平滚动
    $body.addClass('layui-layout-body');
    
    //低版本IE提示
    if(device.ie && device.ie < 10){
      Error('IE'+ device.ie + '下访问可能不佳，推荐使用：Chrome / Firefox / Edge 等高级浏览器', {
        offset: 'auto'
        ,id: 'LAY_errorIE'
      });
    }
    
  }();
  
  admin.prevRouter = {}; //上一个路由
  admin.prevErrorRouter = {}; //上一个异常路由
  
  //监听 hash 改变侧边状态
  admin.on('hash(side)', function(router){
    admin.view('TPL_layout').refresh(function(){
      element.render('nav', 'layadmin-side-child'); //重新渲染子菜单
    });
  });
  
  //左侧导航切换
  element.tab({
    headerElem: '#'+ SIDE_MENU1 +'>li'
    ,bodyElem: '#'+ SIDE_MENU2 +'>.layui-menu-item'
  });
  
  //监听侧边一级菜单切换
  element.on('tab(layadmin-system-menu)', function(obj){
    if(admin.screen() < 2){
      admin.sideFlexible('spread');
      admin.iconFlexible();
    }
  });
  
  //页面跳转
  $body.on('click', '*[lay-href]', function(){
    var othis = $(this)
    ,href = othis.attr('lay-href')
    ,router = layui.router();

    admin.prevRouter[router.path[0]] = router.href; //记录上一次各菜单的路由信息
    location.hash = '/' + href; //执行跳转
  });
  
  //点击事件
  $body.on('click', '*[layadmin-event]', function(){
    var othis = $(this)
    ,attrEvent = othis.attr('layadmin-event');
    events[attrEvent] && events[attrEvent].call(this, othis);
  });
  
  //tips
  $body.on('mouseenter', '*[lay-tips]', function(){
    var othis = $(this)
    ,tips = othis.attr('lay-tips')
    ,offset = othis.attr('lay-offset') 
    ,index = layer.tips(tips, this, {
      tips: 1
      ,time: -1
      ,success: function(layero, index){
        if(offset){
          layero.css('margin-left', offset + 'px');
        }
      }
    });
    othis.data('index', index);
  }).on('mouseleave', '*[lay-tips]', function(){
    layer.close($(this).data('index'));
  });
  
  //窗口resize事件
  layui.data.resizeSystem = function(){
    //layer.close(events.note.index);
    layer.closeAll('tips');
    
    if(admin.screen() < 2){
      admin.sideFlexible()
    } else {
      admin.sideFlexible('spread')
    }
  }
  $win.on('resize', layui.data.resizeSystem);
  
  //接口输出
  exports('admin', admin);
});