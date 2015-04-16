<?php

define('ROOT_PATH', __DIR__.'/photo');
//$dir_root=isset($_GET['d'])?$_GET['d']:'/';
$dir_root=$_GET['d'];
if(empty($dir_root))
{
    $dir_root='/';
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="../../favicon.ico">-->

    <title> 资源库 </title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- <script src="../../assets/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    </style>
</script>
  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">图片目录</a>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">

<?php
function file_type($filename)  
{  
    $file = fopen($filename, "rb");  
    $bin = fread($file, 2); //只读2字节  
    fclose($file);  
    $strInfo = @unpack("C2chars", $bin);  
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);  
    $fileType = '';  
    switch ($typeCode)  
    {  
        case 7790:  
            $fileType = 'exe';  
            break;  
        case 7784:  
            $fileType = 'midi';  
            break;  
        case 8297:  
            $fileType = 'rar';  
            break;          
        case 8075:  
            $fileType = 'zip';  
            break;  
        case 255216:  
            $fileType = 'jpg';  
            break;  
        case 7173:  
            $fileType = 'gif';  
            break;  
        case 6677:  
            $fileType = 'bmp';  
            break;  
        case 13780:  
            $fileType = 'png';  
            break;  
        default:  
            $fileType = 'unknown: '.$typeCode;  
    }  
  
    //Fix  
    if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ) return 'jpg';  
    if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ) return 'png';  
  
    return $fileType;  
}

function listPhoto($dir)
{   
    Global $dir_root;
    $pos = strrpos($dir_root,'/');
    $newstr = substr($dir_root,0,$pos);
    if (empty($newstr))
    {
        $newstr = '/';
    }
    $div_html ="";
    //echo "<div class='container'>";
    if (is_dir($dir)){
        if ($dh = opendir($dir)){
            //显示文件夹html
            $div_html .= '        <div class="col-sm-3 col-md-2 sidebar"></br>';
            $div_html .= '<ul class="nav nav-sidebar">';

            $div_html .= '<li><a href="?d=/" >Root</a></li>';
            $div_html .= "<li><a href='?d=".$newstr."' >..</a></li>";
            
            //显示图片html
            $picture_html = '<div class=" col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">';
            $picture_html .= '<div class="row img-thumbnail">';
            
            while (($file = readdir($dh))!= false){
                //文件名的全路径 包含文件名
                $filePath = $dir.'/'.$file;
                if($file=='.'||$file=='..'||$file=='.DS_Store') continue;
                if (is_dir($filePath))
                {
                    $p_dir = "";
                    if ($dir_root == '/')
                        $p_dir = '/'.$file;
                    else    
                        $p_dir .=$dir_root.'/'.$file;
                    $p_dir = urlencode($p_dir);
                    $div_html .= "<li><a href='?d=".$p_dir."'>$file</a></li>";
                }
                elseif (file_type($filePath) == "jpg")
                {
                    $filename = "photo".$dir_root."/".$file;

                    $name_pos = strripos($file,'.jpg');
                    $name = substr($file,0,$name_pos);
                    $picture_html .= '<div class="col-xs-6 col-md-3 img-thumbnail">';
                    $picture_html .= "<a tabindex='0' class='' role='button' data-toggle='popover' data-placement='auto' data-content='"."\\\\share.rayjoy.com".$dir_root.'/'.$name.'/'."'><img src='".$filename."' class='img-thumbnail' alt='thumbnail'>"."</a>";
                    $picture_html .= "<span class='text-success center-block text-center'>".$file."</span></div>";
                }
            }
            closedir($dh);
            $div_html .= "</ul></div>\n";
            $picture_html .= '</div></div>';
            echo $div_html;
            echo $picture_html;
        }
    }
}
listphoto(ROOT_PATH.$dir_root);
?>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
    
    <script src="bootstrap.min.js"></script>
    <script>
        $('[data-toggle="popover"]').popover();
    </script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <!-- <<script src="../../assets/js/vendor/holder.js"></script> -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
  </body>
</html>
