<?php

use Pboy\Renderer\PhpTemplate;


class PhpTemplateTest extends PHPUnit_Framework_TestCase
{

    public function provideData()
    {
        $data = array(
            array(
                'title' => 'foo bar',
                'slug' => 'foo-bar',
                'content' => 'blah!',
                'date' => '2013/09/14 23:30'
            ),
            array(
                'title' => 'Lorem ipsum',
                'slug' => 'lorem-ipsum',
                'content' => 'dolor sit amet',
                'date' => '2013/04/08 03:30'
            ),
        );

        $Config = array(
            'conf' => array(
                'Rendering' => array(
                    'designs_folder' => 'Design',
                    'design' => array('default')
                )
            )
        );

        $Renderer = new PhpTemplate(array('Config' => $Config));

        return array(array($data, $Renderer));
    }


    /**
     * @dataProvider provideData
     */
    public function testRender($data, $Renderer)
    {
        $view = array(
            'index.html' => '' 
        );

        $template_vars = array(
            'meta_title' => 'foo',
            'meta_description' => 'bar',
            'meta_keywords' => 'keywords',
            'site_title' => 'title',
            'css' => array(),
            'js' => array()
        );
        
        $Renderer->setOutputPath('/tmp/')
                 ->setVariables($template_vars);

        $Renderer->renderView('post.html', 'item', 'test-<slug>.html', $data);
        
    }
}
