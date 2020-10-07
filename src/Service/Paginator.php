<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Paginator
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    /**
     * Paginator constructor.
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param $templatePath
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->twig = $twig;
        $this->manager = $manager;
        $this->templatePath = $templatePath;
        $this->route = $request->getCurrentRequest()->get('_route');
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
        ]);
    }

    /**
     * Calcule le nombre total de pages que produiront le nombre d'elements se trouvant en base de données
     *
     * @return false|float
     * @throws \Exception
     */
    public function getPages()
    {
        if (empty($this->entityClass)){
            throw new \Exception('Precisez l\'entite sur laquelle se base la pagination de cette feuille.');
        }
        $repo = $this->manager->getRepository($this->entityClass);
        $countElement = count($repo->findAll());

        return ceil($countElement/$this->limit);
    }

    /**
     * récupère les enregistrements de la base de données grace au bon repository
     *
     * @return object[]
     * @throws \Exception
     */
    public function getData()
    {
        if (empty($this->entityClass)){
            throw new \Exception('Precisez l\'entite sur laquelle se base la pagination de cette feuille. Vous pourrez utiliser la methode setEntity()');
        }

        if($this->currentPage > (int)$this->getPages() || $this->currentPage <= 0){
            throw new \Exception('La page que vous demandez n\'existe pas');
        }

        $offset = $this->limit * ($this->currentPage - 1);
        $repo = $this->manager->getRepository($this->entityClass);

        return $repo->findBy([],[], $this->limit, $offset);
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): Paginator
    {
        $this->limit = $limit;
        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): Paginator
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * @param mixed $route
     * @return Paginator
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param mixed $templatePath
     * @return Paginator
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }


}