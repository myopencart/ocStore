<?php echo $header; ?>
<h1>Шаг 2 - Перед установкой</h1>
<div id="column-right">
  <ul>
      <li>Лицензия</li>
      <li><b>Перед установкой</b></li>
      <li>Конфигурация</li>
      <li>Окончание</li>
    </ul>
</div>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <p>1. Настройте PHP для соответствия следующим требованиям.</p>
    <fieldset>
      <table>
        <tr>
          <th width="35%" align="left"><b>Настройки PHP</b></th>
            <th width="25%" align="left"><b>Текущие настройки</b></th>
            <th width="25%" align="left"><b>Необходимые настройки</b></th>
            <th width="15%" align="center"><b>Состояние</b></th>
        </tr>
        <tr>
          <td>PHP Version:</td>
          <td><?php echo phpversion(); ?></td>
          <td>5.0+</td>
          <td align="center"><?php echo (phpversion() >= '5.0') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>Register Globals:</td>
          <td><?php echo (ini_get('register_globals')) ? 'On' : 'Off'; ?></td>
          <td>Off</td>
          <td align="center"><?php echo (!ini_get('register_globals')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>Magic Quotes GPC:</td>
          <td><?php echo (ini_get('magic_quotes_gpc')) ? 'On' : 'Off'; ?></td>
          <td>Off</td>
          <td align="center"><?php echo (!ini_get('magic_quotes_gpc')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>File Uploads:</td>
          <td><?php echo (ini_get('file_uploads')) ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo (ini_get('file_uploads')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>Session Auto Start:</td>
          <td><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></td>
          <td>Off</td>
          <td align="center"><?php echo (!ini_get('session_auto_start')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
      </table>
    </fieldset>
    <p>2. Убедитесь, что перечисленные ниже расширения установлены.</p>
    <fieldset>
      <table>
        <tr>
			<th width="35%" align="left"><b>Расширения</b></th>
			<th width="25%" align="left"><b>Текущие настройки</b></th>
			<th width="25%" align="left"><b>Необходимые настройки</b></th>
			<th width="15%" align="center"><b>Состояние</b></th>
        </tr>
        <tr>
          <td>MySQL:</td>
          <td><?php echo extension_loaded('mysql') ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo extension_loaded('mysql') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>GD:</td>
          <td><?php echo extension_loaded('gd') ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo extension_loaded('gd') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>cURL:</td>
          <td><?php echo extension_loaded('curl') ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo extension_loaded('curl') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>mCrypt:</td>
          <td><?php echo function_exists('mcrypt_encrypt') ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo function_exists('mcrypt_encrypt') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
        <tr>
          <td>ZIP:</td>
          <td><?php echo extension_loaded('zlib') ? 'On' : 'Off'; ?></td>
          <td>On</td>
          <td align="center"><?php echo extension_loaded('zlib') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Плохо" />'; ?></td>
        </tr>
      </table>
    </fieldset>
    <p>3. Убедитесь, что перечисленные ниже файлы имеют разрешение на запись.</p>
    <fieldset>
      <table>
        <tr>
          <th align="left"><b>Файл</b></th>
          <th align="left"><b>Состояние</b></th>
        </tr>
        <tr>
          <td><?php echo $config_catalog; ?></td>
          <td><?php if (!file_exists($config_catalog)) { ?>
            <span class="bad">Файл отсутствует</span>
            <?php } elseif (!is_writable($config_catalog)) { ?>
            <span class="bad">Не доступно для записи</span>
          <?php } else { ?>
          <span class="good">Доступно для записи</span>
          <?php } ?>
            </td>
        </tr>
        <tr>
          <td><?php echo $config_admin; ?></td>
          <td><?php if (!file_exists($config_admin)) { ?>
            <span class="bad">Файл отсутствует</span>
            <?php } elseif (!is_writable($config_admin)) { ?>
            <span class="bad">Не доступно для записи</span>
          <?php } else { ?>
          <span class="good">Доступно для записи</span>
          <?php } ?>
             </td>
        </tr>
      </table>
    </fieldset>
    <p>4. Убедитесь, что перечисленные ниже каталоги, а также все их подкаталоги и файлы в них имеют разрешение на запись.</p>
    <fieldset>
      <table>
          <tr>
            <th align="left"><b>Директория</b></th>
            <th width="30%" align="left"><b>Состояние</b></th>
          </tr>
          <tr>
            <td><?php echo $cache . '/'; ?></td>
            <td><?php echo is_writable($cache) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>
          <tr>
            <td><?php echo $logs . '/'; ?></td>
            <td><?php echo is_writable($logs) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>
          <tr>
            <td><?php echo $image . '/'; ?></td>
            <td><?php echo is_writable($image) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>
          <tr>
            <td><?php echo $image_cache . '/'; ?></td>
            <td><?php echo is_writable($image_cache) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>
          <tr>
            <td><?php echo $image_data . '/'; ?></td>
            <td><?php echo is_writable($image_data) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>          
          <tr>
            <td><?php echo $download . '/'; ?></td>
            <td><?php echo is_writable($download) ? '<span class="good">Доступно для записи</span>' : '<span class="bad">Не доступно для записи</span>'; ?></td>
          </tr>
        </table>
    </fieldset>
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button">Назад</a></div>
      <div class="right">
        <input type="submit" value="Продолжить" class="button" />
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?>
