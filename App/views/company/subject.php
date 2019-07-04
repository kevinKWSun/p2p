<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
   <form id="formrec" action="<?php echo $url; ?>" class="layui-form layui-form-pane" method="post">
			<input type="hidden"  name="merchantNo" value="<?php echo $merchantNo; ?>"  />
			<input type="hidden"  name="jsonEnc" value="<?php echo $jsonEnc; ?>"  />
			<input type="hidden"  name="keyEnc" value="<?php echo $keyEnc; ?>"  />
			<input type="hidden"  name="sign" value="<?php echo $sign; ?>"  />
			<input type="hidden"  name="merOrderNo" value="<?php echo $merOrderNo; ?>"  />
			
			<?php  foreach($params as $k=>$v) { ?>
				<input type="hidden"  name="<?php echo $k; ?>" value="<?php echo $v; ?>"  />
			<?php } ?>
			
			
			
			<input type="submit" value="提交" />
        </form>
    </div>
	<script type="text/javascript">
	
	</script>
</body>
</html>