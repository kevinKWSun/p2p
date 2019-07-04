<link href="/src/css/layui.css" rel="stylesheet" />
<script src="/src/layui.js"></script>


<script type="text/javascript">
	layui.use(['layer', 'form'], function () {
		var $ = layui.$
		, layer = layui.layer
		, form = layui.form;
		var index = parent.layer.getFrameIndex(window.name);
		parent.location.reload();
		parent.layer.close(index);
	});
</script>