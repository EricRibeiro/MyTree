<?php


namespace Concedente\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Concedente\Entity\Local;

class LocalConcedenteController extends AbstractActionController
{
    public function indexAction()
    {
        if ($user = $this->identity()) {
            $view_params = array(
                'concedente' => $user,
            );
            return new ViewModel($view_params);

        }
        return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);

    }

    public function cadastrarAction()
    {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $repositorio = $entityManager->getRepository('Concedente\Entity\Local');
       
        if ($this->request->isPost()) {
            $logradouro = $this->request->getPost('logradouro');
            $bairro = $this->request->getPost('bairro');
            $municipio = $this->request->getPost('municipio');
            $uf = $this->request->getPost('uf');
            $cep = $this->request->getPost('cep');
            $numero = "1";
            $complemento = "complemento";
            $latitude = "";
            $longitude = "";
            $concedente = null;

            //$query = $repositorio->createQueryBuilder('o')->where('o.numero = :numeroAnimal')->setParameter('numeroAnimal', $numeroAnimal)->getQuery();
            //$animal = $query->getSingleResult();

            $local = new Local($uf, $municipio, $cep, $bairro, $logradouro, $numero, $complemento, $latitude, $longitude);

                        //var_dump($local->getCep()); exit;

            $entityManager->persist($local);
            $entityManager->flush();
        }

        return $this->redirect()->toRoute('concedente', array(
            'controller' => 'local',
            'action' => 'index',
        ));
    }

    public function removerAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!is_null($id)) {
            $entityManager = $this->sm->get('Doctrine\ORM\EntityManager');
            $repositorio = $entityManager->getRepository("Concedente\Entity\Local");

            $local = $repositorio->find($id);
            $entityManager->remove($local);
            $entityManager->flush();
        }

        return $this->redirect()->toRoute('concedente', array(
            'controller' => 'index',
            'action' => 'index',
        ));
    }

    public function editarAction()
    {
        if ($user = $this->identity()) {
            if ($this->request->isPost()) {

                $id = $this->request->getPost('id');

                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $repositorio = $entityManager->getRepository("Concedente\Entity\Concedente");
                $concedente = $repositorio->find($id);

                $concedente->setNome($this->request->getPost('nome'));
                $concedente->setTelefone($this->request->getPost('celular'));
                $concedente->setEmail($this->request->getPost('email'));
                $concedente->setSenha($this->request->getPost('senha'));

                $entityManager->persist($concedente);
                $entityManager->flush();
            }
            return $this->redirect()->toRoute('concedente', ['controller' => 'local', 'action' => 'index']);

        } else {
            return $this->redirect()->toRoute('application', ['controller' => 'login', 'action' => 'index']);

        }
    }
}