<?php

namespace Jdi;

use Data\BlobFile;

class Task extends TaskBase implements \JsonSerializable
{
    const STATUS_ADDING      = 'adding';
    const STATUS_READY       = 'ready';
    const STATUS_RUNNING     = 'running';
    const STATUS_DONE        = 'done';
    const STATUS_FAIL        = 'fail';
    const STATUS_CANCEL      = 'cancel';
    const STATUS_INTERRUPTED = 'interrupted';
    
    private $stdin = null;
    
    function stdin()
    {
        if (empty($this->stdin)) {
            $this->stdin = new BlobFile(parent::stdin());
            
            if (empty(parent::stdin())) {
                parent::setStdin($this->stdin->guid());
            }
        }
        
        return $this->stdin;
    }
    
    public function setStatusAndSave($value)
    {
        $this->setStatus($value);
        $this->save();
    }
    
    public function makeNewRun()
    {
        $run = new Task\Run();
        $run->setTaskId($this->id());
        
        return $run;
    }
    
    /**
     * @return \Jdi\Task\Run[]
     */
    public function runs()
    {
        return Task\RunRepository::findMany(['taskId' => $this->id()]);
    }
    
    public function delete()
    {
        TaskRepository::delete($this);
    }
    
    
    public function jsonSerialize()
    {
        return [
            'id'      => $this->id(),
            'command' => $this->command(),
//             'stdin'   => $this->stdin()->exists() ? $this->stdin()->path() : false,
            'date'    => js_datetime($this->date()),
            'runAt'   => $this->runAt() ? js_datetime($this->runAt()) : null,
            'status'  => $this->status(),
            'data'    => $this->extra(),
            'runs'    => $this->runs(),
        ];
    }
}