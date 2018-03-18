<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function logarAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $senha = $this->request->getPost('senha');
            $usuario = $this->request->getPost('usuario');
            $route = "";

            $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $authAdapter = $authService->getAdapter();

            switch ($usuario) {
                case 'Plantador':
                    $route = 'pessoa';
                    break;
                case 'Concedente':
                    $route = 'concedente';
                    break;
                case 'Investidor':
                    $route = 'investidor';
                    break;
            }

            $authAdapter->setIdentityValue($email);
            $authAdapter->setCredentialValue($senha);
            $authResult = $authService->authenticate();

            if ($authResult->isValid()) {
                return $this->redirect()->toRoute($route);
            }

            return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);

        }

        return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);
    }

    function logoutAction() {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authService->clearIdentity();
        return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);
    }
}
