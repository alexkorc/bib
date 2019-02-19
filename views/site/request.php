<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin(); ?>
<?php
    $users = User::find()->all();
    // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
    $items = ArrayHelper::map($users,'id','name');
    $params = [
    'prompt' => 'Укажите пользователя'
    ];
    echo $form->field($model, 'user')->dropDownList($items,$params);
    ?>
    <?= $form->field($model, 'id')->hiddenInput(['id' => 'user-id'])->label(false) ?>
    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'text') ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
<?php
$js = <<<JS
        $('#knowledge-uploadimg').on('change', function() {
$('#knowledge-img-info').css({'color':'#767676'});
$('#knowledge-img-info').text('Подождите, изображение загружается');
var itemId = $('#knowledge-id').val();
$.ajax({
type: 'POST',
url: '/knowledge/item/uploadimg?id='+itemId,
data: new FormData($('#knowledge-form')[0]),
// data: new FormData($(this)),
processData: false,
contentType: false,
dataType: 'json',
success: function(data) {
console.log(data);
if (data.success) {
$('#knowledge-img-info').css({'color':'hsl(134, 100%, 31%)'});
$('#knowledge-img-info').text('Изображение загружено');
$('#knowledge-uploadimg').val('');
$('#know_img').attr("src", data.img_url);
} else {
$('#knowledge-img-info').css({'color':'red'});
$('#knowledge-img-info').text('Ошибка загрузки');
}
}
});
});
$('#knowledge-uploadfile').on('change', function() {
$('#knowledge-file-info').css({'color':'#767676'});
$('#knowledge-file-info').text('Подождите, документ загружается');
$.ajax({
type: 'POST',
url: '/knowledge/item/upload',
data: new FormData($('#knowledge-form')[0]),
// data: new FormData($(this)),
processData: false,
contentType: false,
dataType: 'json',
success: function(data) {
console.log(data);
if (data.success) {
$('#knowledge-file-info').css({'color':'hsl(134, 100%, 31%)'});
$('#knowledge-file-info').text('Документ загружен');
$('#knowledge-uploadfile').val('');
$('#knowledge-file-list').append('<div class="form-group"><div class="input-group">'+
        '<input type="hidden" value="'+data.file_id+'" name="Knowledge[files][]" />'+
        '<input type="text" class="form-control" value="'+data.file_name+'" disabled="disabled" />'+
        '<span class="input-group-btn"><button class="btn btn-danger btn-delete-file" type="button"><i class="fa fa-trash-o"></i></button>'+
                        '<a target="_blank" href="'+data.file_url+'" class="btn btn-default"><i class="fa fa-download"></i></a></span>'+
        '</div></div></div>');
} else {
$('#knowledge-file-info').css({'color':'red'});
$('#knowledge-file-info').text('Ошибка загрузки');
}
}
});
});
$('body').on('click', '.btn-delete-file', function (e) {
$(this).parent().parent().parent().parent().remove();
});
JS;

$this->registerJs($js);
