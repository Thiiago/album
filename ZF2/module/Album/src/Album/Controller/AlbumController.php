<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// imort Model\ContatoTable com alias
use Album\Model\AlbumTable as ModelAlbum;
use Album\Model\Album;          // <-- Add this import
use Album\Form\AlbumForm;       // <-- Add this import

class AlbumController extends AbstractActionController
{


    // GET /albuns
    public function indexAction()
    {
        return new ViewModel(array(
             'albuns' => $this->getAlbumTable()->fetchAll(),
         ));
    }
 
    // GET /album/novo
    public function novoAction()
    {
         return array('formAlbum' => new AlbumForm());
    }
 
    // POST /album/adicionar
    public function adicionarAction()
    {
         $form = new AlbumForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $album = new Album();
             $form->setInputFilter($album->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $album->exchangeArray($form->getData());
                 $this->getAlbumTable()->saveAlbum($album);

                 // Redirect to list of albums
                 return $this->redirect()->toRoute('albuns');
             }
         }
         return array('form' => $form);
    }
 
    // GET /albuns/detalhes/id
    public function detalhesAction()
    {
         // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem
            //$this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('albuns');
        }

        try {
            // aqui vai a lógica para pegar os dados referente ao contato
            // 1 - solicitar serviço para pegar o model responsável pelo find
            // 2 - solicitar form com dados desse contato encontrado
            // formulário com dados preenchidos
            $album = $this->getAlbumTable()->getAlbum($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            //$this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('albuns');
        }

        // dados eviados para detalhes.phtml
        return ['album' => $album];
    }
 
    // GET /contatos/editar/id
    public function editarAction()
    {
         // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            //$this->flashMessenger()->addMessage("Contato não encotrado");

            // redirecionar para action index
            return $this->redirect()->toRoute('albuns');
        }

        try {
            // variável com objeto contato localizado
            $album = (array) $this->getAlbumTable()->getAlbum($id);
        } catch (Exception $exc) {
            // adicionar mensagem
            //$this->flashMessenger()->addErrorMessage($exc->getMessage());

            // redirecionar para action index
            return $this->redirect()->toRoute('albuns');
        }

        // objeto form contato vazio
        $form = new AlbumForm();
        // popula objeto form contato com objeto model contato
        $form->setData($album);

        // dados eviados para editar.phtml
        return ['formAlbum' => $form];
    }
 
    // PUT /album/editar/id
    public function atualizarAction()
    {
         // obtém a requisição
        $request = $this->getRequest();

        // verifica se a requisição é do tipo post
        if ($request->isPost()) {
            // instancia formulário
            $form = new AlbumForm();
            // instancia model contato com regras de filtros e validações
            $modelAlbum = new Album();
            // passa para o objeto formulário as regras de viltros e validações
            // contidas na entity contato
            $form->setInputFilter($modelAlbum->getInputFilter());
            // passa para o objeto formulário os dados vindos da submissão 
            $form->setData($request->getPost());

            // verifica se o formulário segue a validação proposta
            if ($form->isValid()) {
                // aqui vai a lógica para atualizar os dados à tabela no banco
                // 1 - popular model com valores do formulário
                $modelAlbum->exchangeArray($form->getData());
                // 2 - atualizar dados do model para banco de dados
                $this->getAlbumTable()->update($modelAlbum);

                // adicionar mensagem de sucesso
                //$this->flashMessenger()
                  //      ->addSuccessMessage("Contato editado com sucesso");

                // redirecionar para action detalhes
                return $this->redirect()->toRoute('albuns', array("action" => "detalhes", "id" => $modelAlbum->id));
            } else { // em caso da validação não seguir o que foi definido
                // renderiza para action editar com o objeto form populado,
                // com isso os erros serão tratados pelo helpers view
                return (new ViewModel())
                                ->setVariable('formAlbum', $form)
                                ->setTemplate('Albuns/albuns/editar');
            }
        }
    }
 
    // DELETE /album/deletar/id
    public function deletarAction()
    {
        // filtra id passsado pela url
        $id = (int) $this->params()->fromRoute('id', 0);

        // se id = 0 ou não informado redirecione para contatos
        if (!$id) {
            // adicionar mensagem de erro
            //$this->flashMessenger()->addMessage("Contato não encotrado");
        } else {
            // aqui vai a lógica para deletar o contato no banco
            // 1 - solicitar serviço para pegar o model responsável pelo delete
            // 2 - deleta contato
            $this->getAlbumTable()->delete($id);

            // adicionar mensagem de sucesso
            //$this->flashMessenger()->addSuccessMessage("Contato de ID $id deletado com sucesso");
        }

        // redirecionar para action index
        return $this->redirect()->toRoute('albuns');
    }

    
    
    protected $albumTable;
    
    public function getAlbumTable()
     {
         if (!$this->albumTable) {
             $sm = $this->getServiceLocator();
             $this->albumTable = $sm->get('Album\Model\AlbumTable');
         }
         return $this->albumTable;
     }
}

