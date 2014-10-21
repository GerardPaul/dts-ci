<?php

class MY_Loader extends CI_Loader {

    public function template($template_name, $vars = array(), $return = FALSE) {
        $content = $this->view('public/templates/header', $vars, $return);
        $content .= $this->view('public/' . $template_name, $vars, $return);
        $content .= $this->view('public/templates/footer', $vars, $return);

        if ($return) {
            return $content;
        }
    }

    public function admin_template($template_name, $vars = array(), $return = FALSE) {
        $content = $this->view('admin/templates/header', $vars, $return);
        $content .= $this->view('admin/' . $template_name, $vars, $return);
        $content .= $this->view('admin/templates/footer', $vars, $return);

        if ($return) {
            return $content;
        }
    }

}