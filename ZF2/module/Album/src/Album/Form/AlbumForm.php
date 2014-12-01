<?php

namespace Album\Form;

 use Zend\Form\Form;

 class AlbumForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('album');

         $this->add(array(
             'name' => 'artista',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Artista',
             ),
         ));
         $this->add(array(
             'name' => 'titulo',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Titulo',
             ),
         ));
         $this->add(array(
             'name' => 'genero',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Gênero',
             ),
         ));
         $this->add(array(
             'name' => 'ano',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Ano',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));
     }
 }
