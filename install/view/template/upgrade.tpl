<?php echo $header; ?>
<h1 style="background: url('view/image/configuration.png') no-repeat;">Обновление базы данных</h1>
<div style="width: 100%; display: inline-block;">
  <div style="float: left; width: 569px;">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <p><b>Внимательно прочтите и выполните следующие действия!</b></p>
	  <ol>
	    <li>О любых ошибках и проблемах при обновлении сообщите на форуме</li>
		<li>После обновления, удалите все куки в своем браузере, чтобы избежать ошибок с токенами.</li>
		<li>Перейдите в Административную панель и дважды нажмите Ctrl+F5 для обновления кешированных CSS стилей.</li>
		<li>Перейдите в разделе Система->Пользователи->Группы пользователей к редактированию группы Главный администратор и отметьте все чекбоксы.</li>
		<li>Перейдите в разделе Система->Настройки к редактированию настроек магазина. Проверьте все значения настроек и нажмите кнопку Сохранить даже если ничего не меняли.</li>
		<li>Перейдите в Публичную часть и дважды нажмите Ctrl+F5 для обновления кешированных CSS стилей.</li>
	  </ol>
      <div style="text-align: right;"><a onclick="document.getElementById('form').submit()" class="button"><span class="button_left button_continue"></span><span class="button_middle">Обновить</span><span class="button_right"></span></a></div>
    </form>
  </div>
  <div style="float: right; width: 205px; height: 400px; padding: 10px; color: #663300; border: 1px solid #FFE0CC; background: #FFF5CC;">
    <ul>
      <li><b>Обновление</b></li>
      <li>Окончание</li>
    </ul>
  </div>
</div>
<?php echo $footer; ?>

