<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Plantador\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Plantador\Entity\Plantador;

class PlantadorController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $viewModel;

    }

    public function cadastrarAction()
    {
        if ($this->request->isPost()) {
            $nome = $this->request->getPost('nome');
            $email = $this->request->getPost('email');
            $senha = $this->request->getPost('senha');
            $telefone = $this->request->getPost('celular');

            $plantador = new Plantador($nome, $email, $senha, $telefone);

            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            try {
                $entityManager->persist($plantador);
                $entityManager->flush();
            } catch (DBALException $e) {
                $this->flashMessenger()->addErrorMessage("O email informado já existe no sistema.");
                return $this->redirect()->toRoute('plantador', ['controller' => 'cadastro', 'action' => 'index']);
            }
            return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);

        }
    }
}
