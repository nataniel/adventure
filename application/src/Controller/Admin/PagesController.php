<?php
namespace Main\Controller\Admin;

use E4u\Application\View;
use Main\Form;
use Main\Model\Page;

class PagesController extends AbstractController
{
    public function indexAction()
    {
        $pages = Page::getRepository()->findAll();

        return [
            'pages' => $pages,
        ];
    }

    public function deleteAction()
    {
        $page = $this->getPageFromParam();
        $page->destroy();

        return $this->redirectBackOrTo('/admin/pages',
            sprintf('Strona <strong>%s</strong> została usunięta.', $page),
            View::FLASH_MESSAGE);
    }

    public function createAction()
    {
        $page = new Page([ 'name' => $this->getRequest()->getQuery('name'), ]);
        $createPage = new Form\CreatePage($this->getRequest(), [ 'page' => $page, ]);

        if ($createPage->isValid()) {
            $page->save();
            return $this->redirectTo('/admin/pages/edit/' . $page->id(),
                'Strona została utworzona.',
                View::FLASH_SUCCESS);
        }

        return [
            'page' => $page,
            'createPage' => $createPage,
        ];
    }

    public function editAction()
    {
        $page = $this->getPageFromParam();
        $editPage = new Form\EditPage($this->getRequest(), [ 'page' => $page, ]);
        if ($editPage->isValid()) {
            $page->save();
            return $this->redirectTo('/admin/pages', 'Zmiany zostały zapisane.', View::FLASH_SUCCESS);
        }

        $choice = new Page\Choice([ 'parent' => $page ]);
        $createChoice = new Form\CreateChoice($this->getRequest(), [ 'choice' => $choice, ]);
        if ($createChoice->isValid()) {
            $choice->save();
            return $this->redirectBackOrTo('/admin/pages/edit/' . $page->id(), 'Wybór został dodany.', View::FLASH_SUCCESS);
        }

        return [
            'page' => $page,
            'editPage' => $editPage,
            'createChoice' => $createChoice,
        ];
    }

    public function removeChoiceAction()
    {
        $choice = $this->getChoiceFromParam();
        $page = $choice->getParent();

        $choice->destroy();
        return $this->redirectTo('/admin/pages/edit/' . $page->id(), 'Wybór został usunięty.', View::FLASH_MESSAGE);
    }

    /**
     * @return Page|null
     */
    private function getPageFromParam()
    {
        $id = (int)$this->getParam('id');
        return Page::find($id);
    }

    /**
     * @return Page\Choice|null
     */
    private function getChoiceFromParam()
    {
        $id = (int)$this->getParam('id');
        return Page\Choice::find($id);
    }
}