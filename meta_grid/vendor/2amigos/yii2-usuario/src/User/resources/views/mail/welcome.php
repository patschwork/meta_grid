<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 * @var \Da\User\Module      $module
 * @var \Da\User\Model\User  $user
 * @var \Da\User\Model\Token $token
 * @var bool                 $showPassword
 */

?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('usuario', 'Hello') ?>,
</p>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <?= Yii::t('usuario', 'Your account on {0} has been created', Yii::$app->name) ?>.
    <?php if ($showPassword || $module->generatePasswords): ?>
        <?= Yii::t('usuario', 'We have generated a password for you') ?>: <strong><?= $user->password ?></strong>
    <?php endif ?>

</p>

<?php if ($token !== null): ?>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Yii::t('usuario', 'In order to complete your registration, please click the link below') ?>.
    </p>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Html::a(Html::encode($token->url), $token->url); ?>
    </p>
    <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
        <?= Yii::t('usuario', 'If you cannot click the link, please try pasting the text into your browser') ?>.
    </p>
<?php endif ?>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    A password reset mail will be sent to you. Please set a safe password.
</p>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    <b>Note:</b>
    <i>
    You can use this instance of Meta#Grid for free.
    Although we care about the best user experience, there is no warranty or service level agreement in using the free service.

    In case you need support, consulting or a premium instance of Meta#Grid please do not hesitate to send us a message via the contact form. 
    <br>
    Have fun 🙂
    <br><br>
    <b>By using this this instance you accept this conditions.</b>
    </i> 
    <br><br>
    <b>Useful Links:</b><br>
    <i>Homepage:</i><a href="https://meta-grid.com">Meta-Grid Homepage (https://meta-grid.com)</a><br>
    <i>Docs:</i><a href="https://docs.meta-grid.com">Docs (https://docs.meta-grid.com)</a><br>
    <i>Report issues or errors:</i><a href="https://github.com/Meta-Grid/meta-grid/issues">GitHub Issues (https://github.com/Meta-Grid/meta-grid/issues)</a><br>

    
</p>
