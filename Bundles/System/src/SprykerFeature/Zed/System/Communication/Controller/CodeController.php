<?php

namespace SprykerFeature\Zed\System\Communication\Controller;



use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class CodeController extends AbstractController{

    public function checkTreeAction()
    {
        $fileList = $this->createFileList();

        foreach ($fileList as &$path) {
            $path = str_replace(APPLICATION_ROOT_DIR, '', $path);
            $path = ltrim($path, '/');
        }

        $treeCreator = new \SprykerFeature_Zed_Library_Code_PathValidator_Tree();
        $validator = new \SprykerFeature_Zed_Library_Code_PathValidator($treeCreator->getTree());
        
        return $this->viewResponse([
            'errors' => $validator->check($fileList)
        ]);
    }

    protected function createFileList()
    {
        $directoryHelper = new \SprykerFeature_Zed_Library_Helper_Directory();
        $classMap1 = $directoryHelper->getFiles(APPLICATION_SOURCE_DIR);
        $classMap2 = $directoryHelper->getFiles(APPLICATION_VENDOR_DIR . '/spryker/');
        $classMap = array_merge($classMap1, $classMap2);
        return $classMap;
    }

    public function facadeApiAction()
    {
         // TODO remove later
    }

    public function zedApiAction()
    {
         // TODO remove later
    }

    public function libraryApiAction()
    {
         // TODO remove later
    }

    public function gitLogAction()
    {
         // TODO remove later
    }

    /**
     * Just prototype - code (but it works)
     * Key is from Yves-Migusta!
     */
    public function relicAction()
    {
        $key = 'ad7e8294603a23290ca1f032649f242d7af5125a1058853';
        $application = '3072056';

        $echo = array();
        exec("curl -X GET 'https://api.newrelic.com/v2/applications/$application/metrics/data.json' -H 'X-Api-Key:$key' -i -d 'names[]=Apdex&summarize=false' ", $echo);
        $result = json_decode(end($echo));

        $data = array();
        foreach ($result->metric_data->metrics as $metric) {
            $i = 0;
            foreach ($metric->timeslices as $timeslice) {

                $dt = new \DateTime($timeslice->from);
                $from  = $dt->format('H:i');

                $item = array(
                    'from' => $from,
                    'value' => $timeslice->values->count,
                );

                $i++;
                $data[] = $item;
            }
        }
        
        return $this->viewResponse([
            'chartData' => $data
        ]);
    }


}