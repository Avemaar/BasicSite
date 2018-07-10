<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">

</nav>
<div class="users form large-9 medium-8 columns content">
    
    <fieldset>
        <legend><?= __('Greetings Page') ?></legend>
        <?php
            
			if($user)
			{
			
			echo 'Greetings to you:';
			echo '<b>';
			echo $user->name;
			echo '</b>';
			echo '!';
			
            }
			
            
        ?>
		
		<?= $this->Form->postButton(__('Signout'),['type'=>'button','controller'=>'users','action'=>'logout']) ?>
    </fieldset>
 
</div>

