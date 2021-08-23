<?php
namespace Main\Controller\Admin;

use E4u\Application\View;
use Main\Form;
use Main\Model\Page;
use Main\Model\Game;

class PagesController extends AbstractController
{
    public function indexAction()
    {
        $this->redirectTo('/admin/games');
    }

    public function deleteAction()
    {
        $page = $this->getPageFromParam();
        $game = $page->getGame();
        $page->destroy();

        $this->redirectBackOrTo('/admin/games/pages/' . $game->id(),
            sprintf('Strona <strong>%s</strong> została usunięta.', $page),
            View::FLASH_MESSAGE);
    }

    public function createAction(): array
    {
        $game = $this->getGameFromParam();
        $page = new Page([ 'name' => $this->getRequest()->getQuery('name'), 'game' => $game ]);
        $createPage = new Form\CreatePage($this->getRequest(), [ 'page' => $page, ]);

        if ($createPage->isValid()) {
            $page->testStatusChanges();
            $page->save();
            $this->redirectTo('/admin/pages/edit/' . $page->id(),
                'Strona została utworzona.',
                View::FLASH_SUCCESS);
        }

        return [
            'page' => $page,
            'createPage' => $createPage,

        ];
    }

    public function editAction(): array
    {
        $page = $this->getPageFromParam();
        $editPage = new Form\EditPage($this->getRequest(), [ 'page' => $page, ]);

        if ($editPage->isValid()) {
            $page->testStatusChanges();
            $page->save();
            $this->redirectToSelf('Zmiany zostały zapisane.', View::FLASH_SUCCESS);
        }

        $choice = new Page\Choice([ 'parent' => $page ]);
        $createChoice = new Form\CreateChoice($this->getRequest(), [ 'choice' => $choice, ]);
        if ($createChoice->isValid()) {
            $choice->testStatusRequirements();
            $choice->save();
            $this->redirectBackOrTo('/admin/pages/edit/' . $page->id(),
                'Wybór został dodany.',
                View::FLASH_SUCCESS);
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
        $this->redirectTo('/admin/pages/edit/' . $page->id(),
            'Wybór został usunięty.', View::
            FLASH_MESSAGE);
    }

    private function getPageFromParam(): ?Page
    {
        $id = (int)$this->getParam('id');
        return Page::find($id);
    }

    private function getChoiceFromParam(): ?Page\Choice
    {
        $id = (int)$this->getParam('id');
        return Page\Choice::find($id);
    }
}