<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Tags Controller
 *
 * @property \App\Model\Table\TagsTable $Tags
 * @method \App\Model\Entity\Tag[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TagsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $tags = $this->paginate();

        $this->set(compact('tags'));
    }

    /**
     * View method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $tag = $this->Tags->get($id, [
            'contain' => ['Bookmarks'],
        ]);

        $this->set(compact('tag'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $tag = $this->Tags->newEmptyEntity();
        if ($this->request->is('post')) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            $tag->user_id = $this->Auth->user('id');
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('タグを保存しました'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('タグが保存できませんでした。もう一度お試しください'));
        }
        $bookmarks = $this->Tags->Bookmarks->find('list', ['limit' => 200]);
        $this->set(compact('tag', 'bookmarks'));
        $this->set('_serialize', ['tag']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $tag = $this->Tags->get($id, [
            'contain' => ['Bookmarks'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tag = $this->Tags->patchEntity($tag, $this->request->getData());
            $tag->user_id = $this->Auth->user('id');
            if ($this->Tags->save($tag)) {
                $this->Flash->success(__('タグを保存しました'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('タグが保存できませんでした。もう一度お試しください'));
        }
        $bookmarks = $this->Tags->Bookmarks->find('list', ['limit' => 200]);
        $this->set(compact('tag', 'bookmarks'));
        $this->set('_serialize', ['tag']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Tag id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $tag = $this->Tags->get($id);
        if ($this->Tags->delete($tag)) {
            $this->Flash->success(__('The tag has been deleted.'));
        } else {
            $this->Flash->error(__('The tag could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        if (in_array($action, ['index', 'add', 'tags'])) {
            return true;
        }
        if (!$this->request->getParam('pass.0')) {
            return false;
        }

        $id = $this->request->getParam('pass.0');
        $tag = $this->Tags->get($id);
        if ($tag->user_id == $user['id']) {
            return true;
        }
        return parent::isAuthorized($user);
    }
}
