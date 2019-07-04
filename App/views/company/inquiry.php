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
        </form>
    </div>
	<script type="text/javascript">
		window.onload= function(){
			document.getElementById('formrec').submit();
		}
	</script>
</body>
</html>