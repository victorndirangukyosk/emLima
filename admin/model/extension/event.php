<?php

class ModelExtensionEvent extends Model
{
    public function addEvent($code, $trigger, $action)
    {
        $exTrigger = explode('.', $trigger);
        $funcitonName = '';

        foreach ($exTrigger as $value) {
            if (!empty($check)) {
                $funcitonName .= ucwords($value);
            } else {
                $check = 1;
                $funcitonName = $value;
            }
        }

        $replaceArray = [
          '_', '-', '.',
        ];

        if (is_file(DIR_CATALOG.'event/app/'.$code.'.php')) {
            $file = file_get_contents(DIR_CATALOG.'event/app/'.$code.'.php', FILE_USE_INCLUDE_PATH);

            $searchString = 'class EventApp'.ucwords(str_replace($replaceArray, '', $code)).' extends Event {';

            $string = 'public function '.$funcitonName.'(&$data) {';

            $function = '

    public function '.$funcitonName.'(&$data) {
        $this->load->controller("'.$action.'", $data);
    }
        ';
            if (false === strpos($file, $string)) {
                $index = str_replace($searchString, $searchString.$function, $file);

                $content = $index;
            } else {
                $content = $file;
            }
        } else {
            $content = '<?php


class EventApp'.ucwords(str_replace($replaceArray, '', $code)).' extends Event {

    public function '.$funcitonName.'(&$data) {
        $this->load->controller("'.$action.'", $data);
    }
}';
        }

        $this->filesystem->dumpFile(DIR_CATALOG.'event/app/'.$code.'.php', $content, 0644);
        //$this->filesystem->dumpFile(DIR_CATALOG . 'event/app/' . $code . '.php', $content, 0644);
    }

    public function deleteEvent($code)
    {
        $this->filesystem->remove(DIR_CATALOG.'event/app/'.$code.'.php');
        //$this->filesystem->remove(DIR_CATALOG . 'event/app/' . $code . '.php');
    }
}
