<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Квитанция СБ РФ (ПД-4)</title>

<style type="text/css">
body {font-family:Arial, Helvetica, sans-serif;/*font-size:14px;*/}
a {color:#006400;}
p {padding: 5px 0px 0px 5px;}
.vas ul {padding: 0px 10px 0px 15px;}
.vas li {list-style-type:circle;}
h3 {padding:0px 0px 0px 5px;font-size:100%;}
h1 {color:#006400;padding:0px 0px 0px 5px;font-size:120%;}
li {list-style-type: none;padding-bottom:5px;padding: 6px 0px 0px 5px;}
.main {font-size:12px;}
.list {font-size:12px;padding: 6px 15px 0px 5px;}
.main input {font-size:12px;background-color:#CCFFCC;}
.text14 {font-family:"Times New Roman", Times, serif;font-size:14px;}
.text14 strong {font-family:"Times New Roman", Times, serif;font-size:11px;}
.link {font-size:12px;}
.link a {text-decoration:none;color:#006400;}
.link_u {font-size:12px;}
.link_u a {color:#006400;}
</style>

<script language="javascript">
function print1() {
	if (confirm ('<?php echo $text_confirm ?>')) {
		window.print();
	} else {
		history.go(-1);
	}
}
</script>
</head>

<body>
<div class="text14">
  <table width="720" bordercolor="#000000" style="border:#000000 1px solid;" cellpadding="0" cellspacing="0">
    <tr>
      <td width="220" valign="top" height="250" align="center" style="border-bottom:#000000 1px solid; border-right:#000000 1px solid;">&nbsp;<strong>Платеж</strong></td>
      <td valign="top" style="border-bottom:#000000 1px solid; border-right:#000000 1px solid;">
      <li><strong>Получатель: </strong> <font style="font-size:90%"> <?php echo $bank ?></font>&nbsp;&nbsp;&nbsp;<br />
        <li><strong>ИНН:</strong> <?php echo $inn ?>&nbsp;&nbsp;<font style="font-size:11px"> &nbsp;</font> <strong>P/сч.:</strong> <?php echo $rs ?>&nbsp;&nbsp;<br />
        <li> <strong>в:</strong> <font style="font-size:90%"><?php echo $bankuser ?></font><br />
        <li><strong>БИК:</strong> <?php echo $bik ?>&nbsp; <strong>К/сч.:</strong> <?php echo $ks ?> <br />
        <li><strong>Платеж:</strong> <font style="font-size:90%">Оплата заказа № <?php echo $order_id ?></font><br />
        <li><strong>Плательщик:</strong> <?php echo $name ?> <br />
        <li><strong>Адрес плательщика:</strong> <font style="font-size:90%"> <?php echo $address ?></font><br />
        <li><strong>ИНН плательщика:</strong> ____________&nbsp;&nbsp;&nbsp;&nbsp; <strong>№ л/сч. плательщика:</strong> ______________
        <li><strong>Сумма:</strong> <?php echo $amount ?> руб. &nbsp;&nbsp;&nbsp;&nbsp;<strong>Сумма оплаты услуг банка:</strong> ______ руб. __ коп.<br />
          <br />
          Подпись:________________________        Дата: &quot; __ &quot;&nbsp;_______&nbsp;&nbsp;20&nbsp;&nbsp; г. <br />
          <br />
      </td>
    </tr>
    <tr>
      <td width="220" valign="top" height="250" align="center" style="border-bottom:#000000 1px solid; border-right:#000000 1px solid;">&nbsp;<strong>Квитанция</strong></td>
      <td valign="top" style="border-bottom:#000000 1px solid; border-right:#000000 1px solid;"> <li><strong>Получатель: </strong> <font style="font-size:90%"> <?php echo $bank ?></font>&nbsp;&nbsp;&nbsp;<br />
        <li><strong>ИНН:</strong> <?php echo $inn ?>&nbsp;&nbsp;<font style="font-size:11px"> &nbsp;</font> <strong>P/сч.:</strong> <?php echo $rs ?>&nbsp;&nbsp;<br />
        <li> <strong>в:</strong> <font style="font-size:90%"><?php echo $bankuser ?></font><br />
        <li><strong>БИК:</strong> <?php echo $bik ?>&nbsp; <strong>К/сч.:</strong> <?php echo $ks ?>
        <li><strong>Платеж:</strong> <font style="font-size:90%">Оплата заказа № <?php echo $order_id ?></font><br />
        <li><strong>Плательщик:</strong> <?php echo $name ?><br />
        <li><strong>Адрес плательщика:</strong> <font style="font-size:90%"> <?php echo $address ?></font><br />
        <li><strong>ИНН плательщика:</strong> ____________&nbsp;&nbsp;&nbsp;&nbsp; <strong>№ л/сч. плательщика:</strong> ______________
        <li><strong>Сумма:</strong> <?php echo $amount ?> руб. &nbsp;&nbsp;&nbsp;&nbsp;<strong>Сумма оплаты услуг банка:</strong> ______ руб. __ коп.<br />
          <br />
          &nbsp;Подпись:________________________        Дата: &quot; __ &quot;&nbsp;_______&nbsp;&nbsp;20&nbsp;&nbsp; г. <br />
          <br />
      </td>
    </tr>
  </table>
</div>

<script language="javascript">
print1();
</script>
</body>
</html>