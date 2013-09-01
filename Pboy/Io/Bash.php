<?php
/**
 * Handles I/O for bash CLI
 */
namespace Pboy\Io;

class Bash extends IoAbstract
{

    /**
     * Main task invoked on script call
     *
     * @var string
     */
     private $task;
        
    /**
     * Options passed to task
     *
     * @var array
     */
     private $options;
    

    /**
     * Internal copy of argv
     * @var string
     */
     public $argv;

    
    /**
     * Call parent constuctor 
     * and preset $argv member
     */
    public function __construct($dependencies = array())
    {
        parent::__construct($dependencies);

        $this->argv = $_SERVER['argv'];
    }

    /**
     * Outputs content to the terminal and adds a new line char
     *
     * @param string $message
     * @param string $type      Message type. Unused for now
     *                          could be useful for styling.
     */
    public function write($message, $type = 'info')
    {
        echo $this->format($message, $type);
    }


    public function format($message, $type = 'info')
    {
        return print_r($message, 1).PHP_EOL;
    }

    /**
     * Outputs content to the terminal 
     * and resets cursor to the begining of line
     * Useful to display progress info on a long task
     * without overflowing terminal
     *
     * @param string
     */
    public function progress($message)
    {
        echo $message."\r";
    }
    

    /**
     * Outputs under condition thaht verbose flag is on
     *
     * @param string $message
     * @param array $options    Options passed to script
     */
    public function verbose($message, $options)
    {
        if (isset($options['verbose'])){
            $this->write($message);
        }
    }
    
    /**
     * Reads input from CLI
     *
     * @param string $question
     * @param bool $showInput   Weither to display chars wehn typing or not.
     *                          Set this to false when asking for a password.
     */
    public function read($question, $showInput = true)
    {
        $option = ($showInput) ? '' : ' -s';

        $command = $this->Config['providers']['Bash']['exec_path'].' -c ';
        $command .= '\'read '.$option.' -p "'. addslashes($question).': "';
        $command .= ' input && echo $input\'';
        
        return rtrim(shell_exec($command));
    }


    public function getTask()
    {
        if (isset($this->argv[1])) {
            return $this->argv[1];
        }

        return false;
    }

    public function getOptions($task)
    {
        $options = $this->Task->options($task);
        
        if (count($options)) {
            $this->Getopt->addOptions($options);
            $this->Getopt->parse( $this->rawOptions() );
        }

        return $this->Getopt->getOptions();
    }


    public function help($task)
    {
        $options = $this->Task->options($task);
            
        $this->Getopt->addOptions($options);
        ob_start();
        $this->Getopt->showHelp();

        return ob_get_clean();
    }
    
    private function rawOptions()
    {
        $argv = $this->argv;

        array_shift($argv);
        array_shift($argv);

        return $argv;
    }



}
