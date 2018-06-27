<form action="<?=\yii\helpers\Url::to(['logo/add'])?>" method="post" enctype="multipart/form-data">
  <input type="file" name="uploadfile" id="uploadfile" />
  <input type="submit" name="submit" value="submit" />
</form>