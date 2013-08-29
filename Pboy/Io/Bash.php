<?php
/**
 * Handles I/O for bash CLI
 */
namespace Pboy\Io;

class Bash extends IoAbstract
{
    /**
     * Outputs content to the terminal and adds a new line char
     *
     * @param string $message
     * @param string $type      Message type. Unused for now
     *                          could be useful for styling.
     */
    public function write($message, $type = 'info')
    {
        echo $message."\n";
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
        $command .= '\'read '.$option.' -p "'. addslashes($question).' "';
        $command .= ' input && echo $input\'';
        
        return rtrim(shell_exec($command));
    }

    public function options($arguments)
    {
        $taskName = $arguments[1];

    }

}
