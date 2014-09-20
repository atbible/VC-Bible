<?php

namespace AndyTruong\Bundle\BibleUIBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class TranslationAdmin extends Admin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', 'text', ['label' => 'Machine name'])
            ->add('writing', 'text', ['label' => 'Human name'])
            ->add('language', 'entity', ['class' => 'AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity'])
            ->add('notes', 'textarea', ['label' => 'Notes'])
        ;
    }

    protected function configureDatagridFilters(\Sonata\AdminBundle\Datagrid\DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ->add('writing')
            ->add('language')
            ->add('notes')
        ;
    }

}
