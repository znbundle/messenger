<?php

/**
 * @var $formView \Symfony\Component\Form\FormView|AbstractType[]
 */

?>

<form id="messageForm" action="" method="post">
    <div class="input-group">
        <input type="hidden" name="chatId" value="<?= $formView->vars['value']->getChatId() ?>">
        <input type="text" name="message" placeholder="Type Message ..." class="form-control">
        <span class="input-group-append">
            <button type="button" class="btn btn-primary" name="submit">Send</button>
        </span>
    </div>
</form>
