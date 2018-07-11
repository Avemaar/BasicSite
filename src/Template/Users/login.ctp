<head>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Login') ?></legend>
        <?php
            echo $this->Form->control('name',['required'=>true]);
            echo $this->Form->control('password',['required'=>true]);
           
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enter')) ?>
    
	<?php if((isset($attempt) && $attempt>=3))
	{
		
		echo '<div class="g-recaptcha" data-sitekey="6LcO0GIUAAAAACFW1W1BDkli_WIXBoc7GsVbn5eo"></div>';
	}
	
	?>
	
	<?= $this->Form->end() ?>
</div>
