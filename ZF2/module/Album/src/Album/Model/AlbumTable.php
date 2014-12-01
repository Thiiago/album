<?php
 
// namespace de localizacao do nosso model
namespace Album\Model;
 
// import Zend\Db
use Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;
 
class AlbumTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getAlbum($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveAlbum(Album $album)
     {
         $data = array(
             'artista' => $album->artista,
             'titulo'  => $album->titulo,
             'genero'  => $album->genero,
             'ano'     => $album->ano,
         );

         $id = (int) $album->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getAlbum($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Album id does not exist');
             }
         }
     }

     public function delete($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
     
     public function update(Album $album)
    {
        //$timeNow = new DateTime();

        $data = [
            'artista'  => $album->artista,
            'titulo'   => $album->titulo,
            'genero'   => $album->genero, 
            'ano'      => $album->ano,
        ];

        $id = (int) $album->id;
        if ($this->getAlbum($id)) {
            $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new Exception("Album #{$id} inexistente");
        }
    }
 }
