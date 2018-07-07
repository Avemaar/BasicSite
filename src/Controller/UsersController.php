<?php
namespace App\Controller;

use App\Controller\AppController;

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
				if($temp)
				{
					$temp->attemp+=1;
					$attempt=$temp->attemp;
					$this->Users->save($temp);
				$this->set(compact('attempt'));
				}
			
			}
		else{
			
			
			$session = $this->getRequest()->getSession();
			$session->write('useridd',$check->id);
			
			
			$this->request->session()->write('userid',$check->id);
			
			//$this->Flash->success($this->request->session()->read('userid'));
			//$this->Flash->success($check->name);
			
			$this->viewBuilder()->layout('inlogged'); 
			$this->layout='inlogged';
			return $this->redirect(['action' => 'greetings',$check->id]);
			
			echo ('found!');}
			
		}
	
	
	
	}
	
	
	public function greetings($id = null)
    {
	
		$this->viewBuilder()->layout('inlogged'); 
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
	
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

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
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
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
