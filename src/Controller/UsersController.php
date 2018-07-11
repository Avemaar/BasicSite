<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Core\Configure;
use Cake\Utility\Security;

use Cake\Mailer\Email;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    
	/*
	*Sajat rész
	*/
	public function login()
	{
	$attempt=0;
		if($this->request->is('post'))
		{
			/*Jelszo titkositott tárolása*/
			$password=Security::hash($this->request->getData('password'));
			$check=$this->Users->find('all')
			->where(['name'=>$this->request->getData('name'),'password'=>$password])
			->first()
			;
		
		
		if(!($check))
			{$this->Flash->error(__('Wrong user or password'));
			
			$temp=$this->Users->find()
			->where(['name'=>$this->request->getData('name')])
			->first()
			;
			
			//debug($temp);
			$captcha=$this->request->getData('g-recaptcha-response'); 	
			if(!$captcha)
			{
			if($temp)
				{
					$temp->attempt+=1;
					$attempt=$temp->attempt;
					$this->Users->save($temp);
				$this->set(compact('attempt'));
				}
			}else

				{
				//debug($captcha);			
				$ip =$this->request->clientIp();
				$secretkey = "6LcO0GIUAAAAAM3dyyLqKAkGZESUPIdjJNKiM6Cs";					
				
				$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".
				
				$captcha."&remoteip=".$ip);
				$responseKeys = json_decode($response,true);	     

				
					if(intval($responseKeys["success"]) == 1) {
							$temp->attempt=0;
							$this->Users->save($temp);
							$this->set(compact('attempt'));
						
						
					}             

				}
			
			
			
			
			
			}
		/*Sikeres belepes*/
		else{
			
			
			$session = $this->getRequest()->getSession();
			$session->write('useridd',$check->id);
			
			
			$this->request->getSession()->write('userid',$check->id);
			
			//$this->Flash->success($this->request->session()->read('userid'));
			//$this->Flash->success($check->name);
			
			$this->viewBuilder()->setLayout('inlogged'); 
			$this->setLayout='inlogged';
			
			
			$check->attempt=0;
			$this->Users->save($check);
			
			return $this->redirect(['action' => 'greetings',$check->id]);
			
			//echo ('found!');}
			
		}
	
	
	
	}
	
	}
	public function greetings($id = null)
    {
	
		$this->viewBuilder()->setLayout('inlogged'); 
		$session = $this->getRequest()->getSession();
		$session->read('useridd');
			
		
		
        if ($id==$session->read('useridd'))
			   // if ($id==$this->request->session()->read('userid'))
				{
				$user = $this->Users->get($id, [
					'contain' => []
				]);

				$this->set(compact('user'));
				}else
					{
					$this->Flash->error(__('Unauthorized Access!'));
					return $this->redirect(['action'=>'login']);
					
					}
    }
	
	public function profile($id = null)
    {
	$this->viewBuilder()->setLayout('inlogged'); 
		
		$session = $this->getRequest()->getSession();
		$session->read('useridd');
			
		
		
        if ($id==$session->read('useridd'))
       
		{
		$user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set(compact('user'));
		}else
		{
		$this->Flash->error(__('Unauthorized Access!'));
		return $this->redirect(['action'=>'login']);
		
		}
    }
	
	
	public function logout()
	{

			
			
			$this->request->getSession()->write('userid',null);
			
			$this->Flash->success(Configure::read('userid'));
			
			return $this->redirect(['action' => 'login']);
			
	}
	
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    // public function index()
    // {
        // $users = $this->paginate($this->Users);

        // $this->set(compact('users'));
    // }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function registration()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            
			$password=Security::hash($this->request->getData('password'));
			$user = $this->Users->patchEntity($user, $this->request->getData());
            $user->password=$password;
			
			if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
				
				$this->sendRegistrationEmail($user);
				
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }
	
	
	
	 public function sendRegistrationEmail($user) {
        $email = new Email();
        $email->template('registration');
        $email->emailFormat('both');
        
		$email->from(['info@diligent.hu'=>'Micro site Registration']);
        $email->to($user->email);
        $email->subject('Registration');
        
		$email->viewVars(['name' => $user->name,'email'=>$user->email]);
        if ($email->send()) {
            $this->Flash->success(__('Check your email for your registration'));
        } else {
            $this->Flash->error(__('Error sending email: ') . $email->smtpError);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
