<?php

namespace App\Controllers;
use App\Helpers\Response;
use App\Helpers\DinamicGlobalConfig;

class ChecklistAnsweredController extends BaseController {

    public function __construct() {
        
    }

    public function setChecklistAnswered($body) {
        $body = json_decode($body, true);
        // guardo la foto
        $informationOfPhotos = $this->savePhoto($body);
        // guardo la firma
        $informationOfFirm = $this->saveFirm($body);
        $data = $this->transformDataForSaveChecklistAnswered($body);
        foreach ($data as $_data) {
            $_data['rut_photox'] = $informationOfPhotos;
            $_data['rut_firmxx'] = $informationOfFirm;
            if (!$this->checklistAnsweredModel->insertData($_data)) {
                return false;
            }
        }
        return true;
    }

    private function transformDataForSaveChecklistAnswered($data) {
        $data = $data['checklist'];
        // obtengo el ultimo consecutivo de la respuesta de la checklist
        $result = $this->checklistAnsweredModel->getLastonsecutiveOfChecklistAnswered();
        $consecutiveCheckilstAnswared = $result['lastConsecutive'] + 1;
        $newData = [];
        // valido que existan categorias en la data
        if (isset($data['categories'])) {
            $h = 0;
            // recorro las categorias
            foreach ($data['categories'] as $category) {
                // valido que exista items en la categoria
                if (isset($category['items'][0])) {
                    // recorro los items 
                    foreach ($category['items'][0] as $item) {
                        if ($this->validateStructureOfTheItem($item)) {
                            if ($item['haveBox'] == '1') {
                                $newData[$h]['ind_respon'] = ( $item['boxValue'] == '1' ? 'S' : 'N');
                            } else {
                                $newData[$h]['ind_respon'] = 'N/A';
                            }
                            $newData[$h]['obs_respon'] = $item['response'];
                            $newData[$h]['usr_respon'] = 'Admin';
                            $newData[$h]['fec_respon'] = date('Y-m-d');
                            $newData[$h]['cod_progra'] = $consecutiveCheckilstAnswared;
                            $newData[$h]['cod_itemxx'] = $item['codeItem'];
                        } else {
                            echo "Estructura invalida";
                        }
                        $h ++;
                    }
                } else {
                    echo "No existen items";
                }
            }
        } else {
            echo "No existen Categorias";
        }
        return $newData;
    }

    private function validateStructureOfTheItem($item) {
        // la estructura es valida hasta que se demuestre lo contrario
        $isValidStructure = true;
        $keysItem = array_keys($item);
        $keysRequire = [
            'codeItem',
            'nameItem',
            'haveBox',
            'response',
            'boxValue',
            'isRequire'
        ];
        foreach ($keysItem as $key) {
            if (!in_array($key, $keysRequire)) {
                $isValidStructure = false;
            }
        }
        return $isValidStructure;
    }

    private function savePhoto($data) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            /* Extraigo la información de la foto: 
             * La primera posición es los datos de la foto
             * La segunda posición es la foto en si. 
             */
            $informationOfPhoto = explode(",", $data['photo']['img']);
            // decodifico la foto, quedando el string de la foto
            $decodedPhoto = base64_decode($informationOfPhoto[1]);
            // transformo la la foto (string) en una foto real
            $realPhoto = imagecreatefromstring($decodedPhoto);
            if ($realPhoto) {
                // defino el nombre de la foto
                $namePhoto = 'photo_' . $dateNow  . ".png";
                // defino la ruta donde se guardará la imagen
                $basePath = DinamicGlobalConfig::getConfig('url_folderx');
                $pathPhoto = "{$basePath}/{$namePhoto}";
                // finalmente se crea la foto y se guarda la foto en una ruta dedicada para las imagenes
                imagepng($realPhoto, $pathPhoto);
            } else {
                return false;
            }
            return $pathPhoto;
        } catch (Exception $ex) {
            Response::responseJSON(['error' => true, 'message' => 'No fue posible crear la foto']);
        }
    }

    private function saveFirm($data) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            /* Extraigo la información de la firma: 
             * La primera posición es los datos de la firma
             * La segunda posición es la firma en si. 
             */
            $informationOfFirm = explode(",", $data['firm']['img']);
            // decodifico la firma, quedando el string de la firma
            $decodedFirm = base64_decode($informationOfFirm[1]);
            // transformo la la firma (string) en una firma real
            $realFirm = imagecreatefromstring($decodedFirm);
            if ($realFirm) {
                // defino el nombre de la firma
                $nameFirm = 'firm_' . $dateNow . ".png";
                // defino la ruta en donde se guardará la imagen
                $basePath = DinamicGlobalConfig::getConfig('url_folderx');
                $pathFirm = "{$basePath}/{$nameFirm}";
                // finalmente se crea la firma y se guarda la firma en una ruta dedicada para las imagenes
                imagepng($realFirm, $pathFirm);
            } else {
                return false;
            }
            return $pathFirm;
        } catch (Exception $ex) {
            Response::responseJSON(['error' => true, 'message' => 'No fue posible crear la firma']);
        }
    }

}
