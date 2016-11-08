<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Email Auto-generado</title>
</head>

<body>
<table width="800" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td bgcolor="#eeeeee"><table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
      <tr>
        
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><table width="760" border="0" align="center" cellpadding="10" cellspacing="0">
          <tr>
            <td>
            	<?php echo $this->fetch('content'); ?>
        	</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td align="center" valign="middle"><?php echo $this->Html->image('logo-duoc.png',array('width'=>'230','fullBase'=>true,'alt'=>"logo-duoc")); ?><!--<img src="images/logo-duocuc.jpg" width="230" height="150" alt="Duoc UC" />--></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>