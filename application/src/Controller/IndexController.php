<?php
namespace Main\Controller;

class IndexController extends AbstractController
{
    protected $requiredPrivileges = [ ];

    public function indexAction()
    {
        return $this->redirectTo('/play');
    }

    public function resetAction()
    {
        $cacheDriver = \E4u\Loader::getDoctrine()->getConfiguration()->getMetadataCacheImpl();
        $cacheDriver->deleteAll();
        return $this->redirectTo('/', 'Pamięć cache została wyczyszczona.');
    }
}