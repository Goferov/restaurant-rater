<?php

class AppController {
    public function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/view/'. $template.'.html';
        $output = 'File not found';

        if(file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;
    }
}