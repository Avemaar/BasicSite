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
        <legend><?= __('View User Profile') ?></legend>
        <?php
            
			if($user)
			{
			echo $user->name;
			echo '<br>';
            echo $user->password;
			echo '<br>';
            echo $user->email;
            }
			
            
        ?>
		
		<?= $this->Form->postButton(__('Signout'),['type'=>'button','controller'=>'users','action'=>'logout']) ?>
    </fieldset>
 
</div>
