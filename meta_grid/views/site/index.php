<?php

use app\models\base\VLastChangesLogList;
use vendor\meta_grid\helper\Utils;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\helpers\Html;

$this->title = \Yii::$app->user->isGuest ? "" : 'Start Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

<?php // setCookie, getCookie, hideMeSomeDays are used for $StartsiteMessages->NewMetaGridVersionCheck() ?>
<script>
function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}
function getCookie(c_name) {
    var i, x, y, thisCookies = document.cookie.split(";");
    for (i = 0; i < thisCookies.length; i++) {
        x = thisCookies[i].substr(0, thisCookies[i].indexOf("="));
        y = thisCookies[i].substr(thisCookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
}
function hideMeSomeDays()
{
    setCookie('hideNewVersionForDays', '3', 3);
    var htmlElement = document.getElementsByName("new-version-info-start")[0];
    htmlElement.hidden = true;

}
</script>
<div class="container-fluid">
    <div class="row">

    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php 


            $StartsiteMessages = new \vendor\meta_grid\helper\StartsiteMessages();
            $StartsiteMessages->NewMetaGridVersionCheck();
            $StartsiteMessages->EmptyObjectTypes();
            $StartsiteMessages->NewRoleCreated();
            $StartsiteMessages->LiquiBaseVersion();
            $StartsiteMessages->DemoDataResetNote();
            $StartsiteMessages->DemoLoginNotes();
            $StartsiteMessages->SQLiteConnectionParameterUpdate();
            $StartsiteMessages->DatabaseIsReadOnly();
            $StartsiteMessages->ConfigUpdateNeeded();


            if (!\Yii::$app->user->isGuest)
            {
                $Utils = new Utils();
                $DbTableField_Model = $Utils->getAllDbTableFieldsTheUserCanSeeCounted();
                $cnt = $DbTableField_Model[0]['cnt'];
                // VarDumper::dump($DbTableField_Model, 10, true);
    
                $VLastChangesLogList_Model = VLastChangesLogList::find()
                ->select(['log_datetime'])
                ->where(['tablename'=>'db_table_field_log'])
                ->all()
                ;
    
                // echo $browser_lang = Yii::$app->request->headers->get('accept-language');
    
                $last_updated = NULL;
                if (array_key_exists(0, $VLastChangesLogList_Model))
                {
                    // $formatter = \Yii::$app->formatter;
                    // $formatted_date = $formatter->asDate($VLastChangesLogList_Model[0]['log_datetime'], 'short');
                    $timestamp = strtotime($VLastChangesLogList_Model[0]['log_datetime']);
                    $last_updated =  date("Y-m-d", $timestamp);    
                }
    
                $smallBox = \hail812\adminlte\widgets\SmallBox::begin([
                    'title' => $cnt,
                    'text' => 'Fields in tables',
                    'icon' => 'fas fa-table',
                    'theme' => 'success',
                    'linkUrl' => Url::to(["/" . 'dbtable/index'])
                ]); 
                echo \hail812\adminlte\widgets\Ribbon::widget([
                    'id' => $smallBox->id.'-ribbon',
                    'text' => ($last_updated != NULL ? $last_updated : "Not updated yet"),
                    // 'text' => '27 added',
                    'theme' => 'warning',
                    'size' => 'lg',
                    'textSize' => 'sm'
                ]);
                \hail812\adminlte\widgets\SmallBox::end();
            }
            ?>
        </div>

    <?php if (\Yii::$app->user->isGuest): ?>

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <center>
                <?php 
                    $m1 = Yii::t('app', 'Welcome to');
                    $m2 = Yii::t('app', 'The data catalog for everybody!');
                    echo "<h1 style=\"font-size: 350%; color: #2D2E2F;\">$m1</h1>";
                    ?>
                <img src="images/animation_BlackAndHighlightColorHex1_jgg7729z.gif">
                </br>
                <?= "<h2 style=\"font-size: 325%; color: #2D2E2F;\">$m2</h2>"; ?>
                </br>
                <?= Html::a(Yii::t('app','Login'), ['/user/security/login'], ['class' => 'btn btn-xl btn-success btn-block']) ?>
            </center>
        </div>
    <?php endif ?>
        
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?php 
                // echo \hail812\adminlte\widgets\SmallBox::widget([
                //     'title' => '150',
                //     'text' => 'Fields in tables',
                //     'icon' => 'fas fa-table',
                // ]) 
                ?>             
            </div>

        <div class="col-lg-6">
            <?php 
            //     echo \hail812\adminlte\widgets\Alert::widget([
            //     'type' => 'success',
            //     'body' => '<h3>Congratulations!</h3>',
            // ]) 
            ?>
            <?php 
            //     echo \hail812\adminlte\widgets\Callout::widget([
            //     'type' => 'danger',
            //     'head' => 'I am a danger callout!',
            //     'body' => 'There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.'
            // ]) 
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <?php 
            $Utils = new Utils();
            $VTagsOptGroup_Model = $Utils->getAllTagsTheUserCanSeeCounted($user_id = NULL);
            foreach ($VTagsOptGroup_Model as $key => $value) {
                echo \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => $value['tag_name'],
                    'number' => 'Objects tagged: ' . $value['cnt'],
                    'icon' => 'fas fa-tag',
                ]);
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\InfoBox::widget([
            //     'text' => 'Messages',
            //     'number' => '1,410',
            //     'icon' => 'far fa-envelope',
            // ]) 
            ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\InfoBox::widget([
            //     'text' => 'Bookmarks',
            //     'number' => '410',
            //      'theme' => 'success',
            //     'icon' => 'far fa-flag',
            // ]) 
            ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\InfoBox::widget([
            //     'text' => 'Uploads',
            //     'number' => '13,648',
            //     'theme' => 'gradient-warning',
            //     'icon' => 'far fa-copy',
            // ]) 
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\InfoBox::widget([
            //     'text' => 'Bookmarks',
            //     'number' => '41,410',
            //     'icon' => 'far fa-bookmark',
            //     'progress' => [
            //         'width' => '70%',
            //         'description' => '70% Increase in 30 Days'
            //     ]
            // ]) 
            ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // $infoBox = \hail812\adminlte\widgets\InfoBox::begin([
            //     'text' => 'Likes',
            //     'number' => '41,410',
            //     'theme' => 'success',
            //     'icon' => 'far fa-thumbs-up',
            //     'progress' => [
            //         'width' => '70%',
            //         'description' => '70% Increase in 30 Days'
            //     ]
            // ]) 
            ?>
            <?php 
            // echo \hail812\adminlte\widgets\Ribbon::widget([
            //     'id' => $infoBox->id.'-ribbon',
            //     'text' => 'Ribbon',
            // ]) 
            ?>
            <?php 
            // \hail812\adminlte\widgets\InfoBox::end() 
            ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\InfoBox::widget([
            //     'text' => 'Events',
            //     'number' => '41,410',
            //     'theme' => 'gradient-warning',
            //     'icon' => 'far fa-calendar-alt',
            //     'progress' => [
            //         'width' => '70%',
            //         'description' => '70% Increase in 30 Days'
            //     ],
            //     'loadingStyle' => true
            // ]) 
            ?>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php 
            // $smallBox = \hail812\adminlte\widgets\SmallBox::begin([
            //     'title' => '150',
            //     'text' => 'New Orders',
            //     'icon' => 'fas fa-shopping-cart',
            //     'theme' => 'success'
            // ]) 
            ?>
            <?php 
            // echo \hail812\adminlte\widgets\Ribbon::widget([
            //     'id' => $smallBox->id.'-ribbon',
            //     'text' => 'Ribbon',
            //     'theme' => 'warning',
            //     'size' => 'lg',
            //     'textSize' => 'lg'
            // ]) 
            ?>
            <?php 
            // \hail812\adminlte\widgets\SmallBox::end() 
            ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php 
            // echo \hail812\adminlte\widgets\SmallBox::widget([
            //     'title' => '44',
            //     'text' => 'User Registrations',
            //     'icon' => 'fas fa-user-plus',
            //     'theme' => 'gradient-success',
            //     'loadingStyle' => true
            // ]) 
            ?>
        </div>
    </div>
</div>
<?php 
    // This are used by $StartsiteMessages->NewMetaGridVersionCheck()
	$this->registerJs("
    cookievalue=getCookie(\"hideNewVersionForDays\");
    if (cookievalue == 3)
    {
        var htmlElement = document.getElementsByName(\"new-version-info-start\")[0];
        htmlElement.hidden = true;
    }
    ; 
	", $this::POS_READY, 'hide-new-version-info'); 
?>
