<?php

namespace Pboy\Controller;

use Pboy\Component\Component;

class Post extends Component implements ControllerInterface
{


    public function run($options = array())
    {
        $this->Io->write('Creating new post');
        $this->Io->write('-----------------');
        $this->Io->write('');

        $questions = $this->Config['tasks']['Post']['infos'];

        $path = 'posts';

        $infos = $this->collectInfo($questions);


        $defaultText = $this->Config['tasks']['Post']['default_text'];
        
        $fileName = $this->create($infos, $defaultText, $path);

        if ($this->Config['tasks']['Post']['open_editor']) {

            $editor = $this->Config['tasks']['Post']['editor_exec_path'];
            $args  = $this->Config['tasks']['Post']['editor_args'];
            $command = "$editor $fileName $args";

            passthru($command);

            $generate = $this->Io->read("Do you want to generate blog ? [Y/n]");

            if (!in_array(strtolower($generate), array('n', 'no'))) {
                $this->Task->execute('generate');
            }


        } else {
            $this->Io->write("Your new post is in $fileName");
        }

        return true;

    }


    private function collectInfo($questions)
    {
        foreach ($questions as $var => $question) {
            if ($question != '') {
                $questions[$var] = $this->Io->read($question);
            }
        }

        return $questions;
    }

    private function create($infos, $text, $path)
    {
        if (!$infos['date']) {
            $infos['date'] = date('Y/m/d H:i', time());
        }

        $infos['slug'] = $this->uniqueSlug($infos['title'], $path);
        $infos['content'] = PHP_EOL.$text.PHP_EOL;
        
        $ref = $this->Input->saveItem($infos, $path);

        return $ref;
    }


    private function uniqueSlug($name, $path, $i = 0)
    {
        $name = $this->createSlug($name);

        do {
            $test = $name;

            if ($i > 0) {
                $test .= "-$i";
            }

            $i++;
        } while (file_exists($path.DIRECTORY_SEPARATOR.$test));

        return $test;
    }

    private function createSlug($name) {

        $name = strtolower($name);
        $name = strtr($name, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿ','aaaaaceeeeiiiinooooouuuuyy');
        $name = preg_replace('/[^a-z0-9]/', ' ', $name);
        $name = preg_replace('/\s+/', '-', $name);
        $name = trim($name, ' -');
    
        return $name;
    }



}
