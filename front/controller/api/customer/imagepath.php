<?php

class ControllerApiCustomerImagepath extends Controller {

    public function index($image) {
        $this->load->model('tool/image');
        $thumb_width = $this->config->get('config_image_thumb_width', 300);
        $thumb_height = $this->config->get('config_image_thumb_height', 300);

        $tmpImg = $image;
        if (!empty($image)) {
            $image = $this->model_tool_image->resize($image, $thumb_width, $thumb_height);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', $thumb_width, $thumb_height);
        }

        if ($this->request->server['HTTPS']) {
            $image = str_replace($this->config->get('config_ssl'), '', $image);
        } else {
            $image = str_replace($this->config->get('config_url'), '', $image);
        }
        return $image;
    }

}
?>