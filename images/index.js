 //新闻滚动
function AutoScroll(obj) {
	$(obj).find("ul:first").animate({
		marginTop: "-50px"
	}, 500, function () {
		$(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
	});
}
//新闻滚动定时器
setInterval('AutoScroll("#pg-header-post-cnt")', 4000);
//加载投标列表
