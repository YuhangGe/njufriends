<?php
static $types = array("image/gif", "image/bmp", "image/jpeg", "image/png");
$onload_func = null;

if (!empty($_FILES)) {
	if (in_array($_FILES["file"]["type"], $types) && ($_FILES["file"]["size"] < 1024 * 1024 * 3)) {
		if ($_FILES["file"]["error"] > 0) {
			$onload_func = "upload_callback(false);";
		} else {
			$ext = explode(".", $_FILES["file"]["name"]);
			$ext_c = count($ext);
			if($ext_c<2)
				$ext = ".jpg";
			else
				$ext = $ext[$ext_c-1];
			$file_name = "upload_images/".date("m-d-H-i-s").rand().".".$ext;
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_name)) {
				$onload_func = "upload_callback(true, {url:'$file_name',id:234});";
			} else {
				$onload_func = "upload_callback(false);";
			}
		}
	} else {
		$onload_func = "upload_callback(false);";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>呼朋唤友</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<style>
			* {
				margin: 0px;
				padding: 0px;
			}
			.file_btn {
				display: inline-block;
				position: relative;
				width: 120px;
				height: 30px;
				overflow: hidden;
			}
			.file_btn input {
				position: absolute;
				right: 0px;
				font-size: 40px;
				height: 30px;
				opacity: 0;
				filter: alpha(opacity:0);
				cursor: pointer;
			}
			.file_btn a {
				display: block;
				position: absolute;
				text-decoration: none;
				color: #005eac;
				left: 0px;
				top: 0px;
				padding: 5px;
				border: 1px solid #cee1ee;
				border-radius: 2px;
				background: #f0f7fc;
			}
		</style>
		<script type="text/javascript">
			function upload(input) {
				document.getElementById("form_upload").submit();
			}
			<?php
			if (!empty($onload_func))
				echo "window.onload = function(){window.parent.$onload_func }";
			?></script>
	</head>
	<body>
		<form id="form_upload" method="post" enctype="multipart/form-data">
			<span class="file_btn"> <a href="javascript:void();" >选择图片文件</a>
				<input onchange="upload(this);" name="file" type="file" size="0"/>
			</span>
		</form>
	</body>
</html>
