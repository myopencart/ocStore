<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-12"><img src="view/image/logo.png" alt="ocStore" title="ocStore" /></div>
    </div>
  </header>
  <h1>Обновление</h1>
  <div class="row">
    <div class="col-sm-9">
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <fieldset>
          <p><b>После установки обновления, обязательно проделайте следующее:</b></p>
          <ol>
            <li>Очистите кеш браузера и удалите всё что находится в папках <b>system/storage/cache</b> и <b>image/cache</b>.</li>
            <li>Перейдите в панель Администратора и <i>(дважды)</i> нажмите Ctrl+F5 чтобы обновить CSS изменения.</li>
            <li>Перейдите в Система -> Пользователи -> Группы пользователей и убедитесь что для Вас выставлены все права.</li>
            <li>Перейдите в Система -> Настройки и обновив все поля - нажмите кнопку Сохранить, даже если ничего не изменилось!</li>
            <li>Откройте Витрину магазина  и <i>(дважды)</i> нажмите Ctrl+F5 чтобы обновить CSS изменения.</li>
            <li>Убедитесь, что в файле журнала ошибок, который хранится в папке <b>system/storage/logs</b> - нет никаких записей!</li>
            <li>Если у Вас, появились какие либо ошибки или проблемы, обязательно сообщите о них на форуме техподдержки!</li>
          </ol>
        </fieldset>
        <div class="buttons">
          <div class="text-right">
            <input type="submit" value="Продолжить" class="button" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item"><b>Обновление</b></li>
        <li class="list-group-item">Завершение обновления</li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>